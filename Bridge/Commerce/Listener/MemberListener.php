<?php

namespace Ekyna\Bundle\MailchimpBundle\Bridge\Commerce\Listener;

use Ekyna\Bundle\MailchimpBundle\Event\MemberEvents;
use Ekyna\Bundle\MailchimpBundle\Model\MemberInterface;
use Ekyna\Bundle\MailchimpBundle\Model\MemberStatuses;
use Ekyna\Component\Commerce\Customer\Repository\CustomerRepositoryInterface;
use Ekyna\Component\Resource\Event\ResourceEventInterface;
use Ekyna\Component\Resource\Exception\UnexpectedTypeException;
use Ekyna\Component\Resource\Persistence\PersistenceHelperInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class MemberListener
 * @package Ekyna\Bundle\MailchimpBundle\Bridge\Commerce\Listener
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class MemberListener implements EventSubscriberInterface
{
    /**
     * @var PersistenceHelperInterface
     */
    private $persistenceHelper;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;


    /**
     * Constructor.
     *
     * @param PersistenceHelperInterface  $persistenceHelper
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        PersistenceHelperInterface $persistenceHelper,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->persistenceHelper  = $persistenceHelper;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Member insert event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onMemberInsert(ResourceEventInterface $event): void
    {
        $this->syncCustomerWithMember($this->getMemberFromEvent($event));
    }

    /**
     * Member update event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onMemberUpdate(ResourceEventInterface $event): void
    {
        $this->syncCustomerWithMember($this->getMemberFromEvent($event));
    }

    /**
     * Member delete event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onMemberDelete(ResourceEventInterface $event): void
    {
        $member = $this->getMemberFromEvent($event);

        $customer = $this->customerRepository->findOneByEmail($member->getEmailAddress());

        if (!$customer || !$customer->isNewsletter()) {
            return;
        }

        $customer->setNewsletter(false);

        $this->persistenceHelper->persistAndRecompute($customer, false);
    }

    /**
     * Synchronizes customer with the given member.
     *
     * @param MemberInterface $member
     */
    protected function syncCustomerWithMember(MemberInterface $member): void
    {
        $customer = $this->customerRepository->findOneByEmail($member->getEmailAddress());

        if (!$customer) {
            return;
        }

        if (($member->getStatus() === MemberStatuses::SUBSCRIBED) && !$customer->isNewsletter()) {
            $customer->setNewsletter(true);

            $this->persistenceHelper->persistAndRecompute($customer, false);

            return;
        }

        if (($member->getStatus() !== MemberStatuses::SUBSCRIBED) && $customer->isNewsletter()) {
            $customer->setNewsletter(false);

            $this->persistenceHelper->persistAndRecompute($customer, false);

            return;
        }
    }

    /**
     * Returns the member from the event.
     *
     * @param ResourceEventInterface $event
     *
     * @return MemberInterface
     */
    protected function getMemberFromEvent(ResourceEventInterface $event): MemberInterface
    {
        $member = $event->getResource();

        if (!$member instanceof MemberInterface) {
            throw new UnexpectedTypeException($member, MemberInterface::class);
        }

        return $member;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            MemberEvents::INSERT => ['onMemberInsert', 0],
            MemberEvents::UPDATE => ['onMemberUpdate', 0],
            MemberEvents::DELETE => ['onMemberDelete', 0],
        ];
    }
}
