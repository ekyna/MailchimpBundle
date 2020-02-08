<?php

namespace Ekyna\Bundle\MailchimpBundle\Listener;

use Doctrine\ORM\EntityManagerInterface;
use Ekyna\Bundle\MailchimpBundle\Event\WebhookEvent;
use Ekyna\Bundle\MailchimpBundle\Model\MemberInterface;
use Ekyna\Bundle\MailchimpBundle\Model\MemberStatuses;
use Ekyna\Bundle\MailchimpBundle\Repository\AudienceRepository;
use Ekyna\Bundle\MailchimpBundle\Repository\MemberRepository;
use Ekyna\Bundle\MailchimpBundle\Service\Mailchimp;
use Ekyna\Bundle\MailchimpBundle\Service\ToggleListenersTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class WebhookListener
 * @package Ekyna\Bundle\MailchimpBundle\Listener
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
final class WebhookListener implements EventSubscriberInterface
{
    use ToggleListenersTrait;

    /**
     * @var MemberRepository
     */
    private $memberRepository;

    /**
     * @var AudienceRepository
     */
    private $audienceRepository;

    /**
     * @var EntityManagerInterface
     */
    private $manager;


    /**
     * Constructor.
     *
     * @param MemberRepository       $memberRepository
     * @param AudienceRepository     $audienceRepository
     * @param EntityManagerInterface $manager
     * @param array                  $listeners
     */
    public function __construct(
        MemberRepository $memberRepository,
        AudienceRepository $audienceRepository,
        EntityManagerInterface $manager,
        array $listeners
    ) {
        $this->memberRepository   = $memberRepository;
        $this->audienceRepository = $audienceRepository;
        $this->manager            = $manager;

        foreach ($listeners as $listener) {
            $this->addListener($listener);
        }
    }

    /**
     * Subscribe event handler.
     *
     * @param WebhookEvent $event
     */
    public function onSubscribe(WebhookEvent $event)
    {
        $data = $event->getData();

        $audience = $this->audienceRepository->findOneByIdentifier($data['list_id']);
        if (!$audience) {
            return;
        }

        $member = $this->memberRepository->findOneByAudienceAndEmail($audience, $data['email']);

        if (null === $member) {
            /** @var MemberInterface $member */
            $member = $this->memberRepository->createNew();
            $member
                ->setAudience($audience)
                ->setIdentifier(Mailchimp::subscriberHash($data['email']))
                ->setWebIdentifier($data['web_id'])
                ->setEmailAddress($data['email'])
                ->setMergeFields($data['merges']);
        }

        $member->setStatus(MemberStatuses::SUBSCRIBED);

        $this->persist($member);
    }

    /**
     * Unsubscribe event handler.
     *
     * @param WebhookEvent $event
     *
     *
     */
    public function onUnsubscribe(WebhookEvent $event)
    {
        if (null === $member = $this->findMember($event)) {
            return;
        }

        $member->setStatus(MemberStatuses::UNSUBSCRIBED);

        $this->persist($member);
    }

    /**
     * Profile update event handler.
     *
     * @param WebhookEvent $event
     */
    public function onProfileUpdate(WebhookEvent $event)
    {
        if (null === $member = $this->findMember($event)) {
            return;
        }

        $data = $event->getData();

        $member
            ->setWebIdentifier($data['web_id'])
            ->setMergeFields($data['merges']);

        $this->persist($member);
    }

    /**
     * Email update event event handler.
     *
     * @param WebhookEvent $event
     */
    public function onEmailUpdate(WebhookEvent $event)
    {
        $data = $event->getData();

        $audience = $this->audienceRepository->findOneByIdentifier($data['list_id']);
        if (!$audience) {
            return;
        }

        $member = $this->memberRepository->findOneByAudienceAndEmail($audience, $data['old_email']);

        $member
            ->setIdentifier(Mailchimp::subscriberHash($data['new_email']))
            ->setEmailAddress($data['new_email']);

        $this->persist($member);
    }

    /**
     * Cleaned email event handler.
     *
     * @param WebhookEvent $event
     */
    public function onCleaned(WebhookEvent $event)
    {
        if (null === $member = $this->findMember($event)) {
            return;
        }

        $member->setStatus(MemberStatuses::CLEANED);

        $this->persist($member);
    }

    /**
     * Finds the member from the event.
     *
     * @param WebhookEvent $event
     *
     * @return MemberInterface|null
     */
    private function findMember(WebhookEvent $event): ?MemberInterface
    {
        $data = $event->getData();

        $audience = $this->audienceRepository->findOneByIdentifier($data['list_id']);
        if (!$audience) {
            return null;
        }

        return $this->memberRepository->findOneByAudienceAndEmail($audience, $data['email']);
    }

    /**
     * Persists the member.
     *
     * @param MemberInterface $member
     */
    private function persist(MemberInterface $member): void
    {
        $this->disableListeners();

        $this->manager->persist($member);
        $this->manager->flush();
        $this->manager->clear();

        $this->enableListeners();
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            WebhookEvent::SUBSCRIBE   => 'onSubscribe',
            WebhookEvent::UNSUBSCRIBE => 'onUnsubscribe',
            WebhookEvent::PROFILE     => 'onProfileUpdate',
            WebhookEvent::UPEMAIL     => 'onEmailUpdate',
            WebhookEvent::CLEANED     => 'onCleaned',
        ];
    }
}
