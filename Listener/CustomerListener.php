<?php

namespace Ekyna\Bundle\MailchimpBundle\Listener;

use Ekyna\Bundle\MailchimpBundle\Model\MemberInterface;
use Ekyna\Bundle\MailchimpBundle\Model\MemberStatuses;
use Ekyna\Bundle\MailchimpBundle\Repository\AudienceRepository;
use Ekyna\Bundle\MailchimpBundle\Repository\MemberRepository;
use Ekyna\Component\Commerce\Customer\Event\CustomerEvents;
use Ekyna\Component\Commerce\Customer\Model\CustomerInterface;
use Ekyna\Component\Commerce\Exception\InvalidArgumentException;
use Ekyna\Component\Resource\Event\ResourceEventInterface;
use Ekyna\Component\Resource\Persistence\PersistenceHelperInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class CustomerListener
 * @package Ekyna\Bundle\MailchimpBundle\Listener
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class CustomerListener implements EventSubscriberInterface
{
    /**
     * @var PersistenceHelperInterface
     */
    private $persistenceHelper;

    /**
     * @var AudienceRepository
     */
    private $audienceRepository;

    /**
     * @var MemberRepository
     */
    private $memberRepository;


    /**
     * Constructor.
     *
     * @param PersistenceHelperInterface $persistenceHelper
     * @param AudienceRepository         $audienceRepository
     * @param MemberRepository           $memberRepository
     */
    public function __construct(
        PersistenceHelperInterface $persistenceHelper,
        AudienceRepository $audienceRepository,
        MemberRepository $memberRepository
    ) {
        $this->persistenceHelper = $persistenceHelper;
        $this->audienceRepository = $audienceRepository;
        $this->memberRepository = $memberRepository;
    }

    /**
     * Newsletter subscribe event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onNewsletterSubscribe(ResourceEventInterface $event)
    {
        $customer = $this->getCustomerFromEvent($event);

        $email = $customer->getEmail();

        $audiences = $this->audienceRepository->findByCustomerGroup($customer->getCustomerGroup());

        foreach ($audiences as $audience) {
            $member = $this->memberRepository->findOneByAudienceAndEmail($audience, $email);
            if (null === $member) {
                $member = $this->memberRepository->createNew();
                $member
                    ->setAudience($audience)
                    ->setEmailAddress($email)
                    ->setMergeFields([
                        'LNAME' => $customer->getLastName(),
                        'FNAME' => $customer->getFirstName(),
                        'BIRTHDAY' => ($date = $customer->getBirthday()) ? $date->format('Y-m-d') : '',
                    ]);
            }

            $member->setStatus(MemberStatuses::SUBSCRIBED);

            $this->persistenceHelper->persistAndRecompute($member, true);
        }
    }

    /**
     * Newsletter unsubscribe event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onNewsletterUnsubscribe(ResourceEventInterface $event)
    {
        $customer = $this->getCustomerFromEvent($event);

        $members = $this->memberRepository->findBy(['emailAddress' => $customer->getEmail()]);

        /** @var MemberInterface $member */
        foreach ($members as $member) {
            $member->setStatus(MemberStatuses::UNSUBSCRIBED);

            $this->persistenceHelper->persistAndRecompute($member, true);
        }
    }

    /**
     * Returns the customer from the event.
     *
     * @param ResourceEventInterface $event
     *
     * @return CustomerInterface
     */
    private function getCustomerFromEvent(ResourceEventInterface $event): CustomerInterface
    {
        $resource = $event->getResource();

        if (!$resource instanceof CustomerInterface) {
            throw new InvalidArgumentException('Expected instance of ' . CustomerInterface::class);
        }

        return $resource;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            CustomerEvents::NEWSLETTER_SUBSCRIBE   => 'onNewsletterSubscribe',
            CustomerEvents::NEWSLETTER_UNSUBSCRIBE => 'onNewsletterUnsubscribe',
        ];
    }
}
