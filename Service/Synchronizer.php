<?php

namespace Ekyna\Bundle\MailchimpBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Ekyna\Bundle\MailchimpBundle\Model\AudienceInterface;
use Ekyna\Bundle\MailchimpBundle\Model\MemberInterface;
use Ekyna\Bundle\MailchimpBundle\Model\Webhook;
use Ekyna\Bundle\MailchimpBundle\Repository\AudienceRepository;
use Ekyna\Bundle\MailchimpBundle\Repository\MemberRepository;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class Synchronizer
 * @package Ekyna\Bundle\MailchimpBundle\Service
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class Synchronizer
{
    use ToggleListenersTrait;

    /**
     * @var Mailchimp
     */
    private $api;

    /**
     * @var AudienceRepository
     */
    private $audienceRepository;

    /**
     * @var MemberRepository
     */
    private $memberRepository;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $audienceIdentifiers;


    /**
     * Constructor.
     *
     * @param Mailchimp              $api
     * @param AudienceRepository     $audienceRepository
     * @param MemberRepository       $memberRepository
     * @param EntityManagerInterface $manager
     * @param UrlGeneratorInterface  $urlGenerator
     * @param array                  $listeners
     */
    public function __construct(
        Mailchimp $api,
        AudienceRepository $audienceRepository,
        MemberRepository $memberRepository,
        EntityManagerInterface $manager,
        UrlGeneratorInterface $urlGenerator,
        array $listeners
    ) {
        $this->api                = $api;
        $this->audienceRepository = $audienceRepository;
        $this->memberRepository   = $memberRepository;
        $this->manager            = $manager;
        $this->urlGenerator       = $urlGenerator;

        $this->listeners = [];
        foreach ($listeners as $listener) {
            $this->addListener($listener);
        }

        $this->logger = new NullLogger();
    }

    /**
     * Sets the logger
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * Synchronizes the audiences.
     */
    public function synchronize(): void
    {
        $this->audienceIdentifiers = [];

        $this->disableListeners();

        $this->syncAudiences();

        $this->configureWebhooks();

        $this->purgeAudiences();

        $this->enableListeners();
    }

    /**
     * Synchronizes audiences.
     */
    protected function syncAudiences(): void
    {
        $this->logger->info('Synchronizing audiences');

        $count = 0;
        foreach ($this->api->getLists() as $data) {
            $audienceIdentifiers[] = $identifier = (string)$data['id'];

            if (null === $audience = $this->audienceRepository->findOneByIdentifier($identifier)) {
                /** @var AudienceInterface $audience */
                $audience = $this->audienceRepository->createNew();
                $audience
                    ->setIdentifier($identifier)
                    ->setSecret($secret = md5(random_bytes(32)));
            }

            $count++;

            if ($this->syncAudience($audience, $data)) {
                $this->logger->info(sprintf("Audience '%s': updated", $audience->getName()));
                $this->manager->persist($audience);
            } else {
                $this->logger->info(sprintf("Audience '%s': up to date", $audience->getName()));
            }

            $this->syncMembers($audience);

            if (0 === $count % 5) {
                $this->manager->flush();
                $this->manager->clear();
            }
        }

        if (0 !== $count % 5) {
            $this->manager->flush();
        }
        $this->manager->clear();
    }

    /**
     * Configures audience's webhooks.
     */
    protected function configureWebhooks(): void
    {
        $this->logger->info('Configuring webhooks');

        $count = 0;
        $audiences = $this->audienceRepository->findWithWebhookNotConfigured();
        foreach ($audiences as $audience) {
            $identifier = $audience->getIdentifier();

            $url = $this->urlGenerator->generate(
                'ekyna_mailchimp_webhook',
                ['secret' => $audience->getSecret()],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            $result = $this->api->post("lists/$identifier/webhooks", [
                'url'     => $url,
                'events'  => array_fill_keys(Webhook::getTypes(), true),
                'sources' => array_fill_keys([Webhook::SOURCE_USER, Webhook::SOURCE_ADMIN], true),
            ]);

            if (!$this->api->success()) {
                $this->logger->error("Failed to configure webhook for audience $identifier.");

                if (isset($result['errors'])) {
                    foreach ($result['errors'] as $error) {
                        if (isset($error['field']) && isset($error['message'])) {
                            $this->logger->error(sprintf("%s:%s", $error['field'], $error['message']));
                        }
                    }
                }
                continue;
            }

            $audience->setWebhook(true);
            $this->manager->persist($audience);
            $count++;

            $this->logger->info(sprintf("Audience '%s' : configured", $audience->getName()));
        }

        if (0 < $count) {
            $this->manager->flush();
        }
        $this->manager->clear();
    }

    /**
     * Purges audiences.
     */
    protected function purgeAudiences(): void
    {
        $this->logger->info('Removing audiences');
        $removedAudiences = $this->audienceRepository->findByNotIds($this->audienceIdentifiers);
        if (empty($removedAudiences)) {
            $this->logger->info('No audience to remove.');

            return;
        }

        foreach ($removedAudiences as $audience) {
            $this->logger->info(sprintf("Audience '%s': removed", $audience->getName()));
            $this->manager->remove($audience);
        }

        $this->manager->flush();
        $this->manager->clear();
    }

    /**
     * Synchronizes the given audience and data.
     *
     * @param AudienceInterface $audience
     * @param array             $data
     *
     * @return bool Whether the audience has been changed.
     */
    protected function syncAudience(AudienceInterface $audience, array $data): bool
    {
        $changed = false;

        if ($audience->getWebIdentifier() !== $identifier = (string)$data['web_id']) {
            $audience->setWebIdentifier($identifier);
            $changed = true;
        }

        if ($audience->getName() !== $name = (string)$data['name']) {
            $audience->setName($name);
            $changed = true;
        }

        return $changed;
    }

    /**
     * Synchronizes audience's members.
     *
     * @param AudienceInterface $audience
     */
    protected function syncMembers(AudienceInterface $audience): void
    {
        foreach ($this->api->getAudienceMembers($audience) as $data) {
            $identifiers[] = $identifier = (string)$data['id'];

            if (null === $member = $this->memberRepository->findOneByIdentifier($identifier)) {
                /** @var MemberInterface $member */
                $member = $this->memberRepository->createNew();
                $member->setIdentifier($identifier);
            }

            if ($this->syncMember($audience, $member, $data)) {
                $this->logger->info(sprintf('Member %s : updated', $member->getEmailAddress()));
                $this->manager->persist($member);
            } else {
                $this->logger->info(sprintf('Member %s : up to date', $member->getEmailAddress()));
            }
        }
    }

    /**
     * Synchronizes the given member and data.
     *
     * @param AudienceInterface $audience
     * @param MemberInterface   $member
     * @param array             $data
     *
     * @return bool
     */
    protected function syncMember(AudienceInterface $audience, MemberInterface $member, array $data): bool
    {
        $changed = false;

        if ($audience !== $member->getAudience()) {
            $member->setAudience($audience);
            $changed = true;
        }

        if ($member->getWebIdentifier() !== $identifier = (string)$data['web_id']) {
            $member->setWebIdentifier($identifier);
            $changed = true;
        }

        if ($member->getEmailAddress() !== $address = (string)$data['email_address']) {
            $member->setEmailAddress($address);
            $changed = true;
        }

        if ($member->getStatus() !== $status = (string)$data['status']) {
            // TODO Check status constant
            $member->setStatus($status);
            $changed = true;
        }

        if ($member->getMergeFields() != $data['merge_fields']) {
            $member->setMergeFields($data['merge_fields']);
            $changed = true;
        }

        // TODO interests, ...

        return $changed;
    }
}
