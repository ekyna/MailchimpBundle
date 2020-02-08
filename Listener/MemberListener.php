<?php

namespace Ekyna\Bundle\MailchimpBundle\Listener;

use Ekyna\Bundle\MailchimpBundle\Event\MemberEvents;
use Ekyna\Bundle\MailchimpBundle\Exception\ApiException;
use Ekyna\Bundle\MailchimpBundle\Model\MemberInterface;
use Ekyna\Component\Resource\Event\ResourceEventInterface;
use Ekyna\Component\Resource\Exception\UnexpectedTypeException;

/**
 * Class MemberListener
 * @package Ekyna\Bundle\MailchimpBundle\Listener
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class MemberListener extends AbstractListener
{
    /**
     * Insert event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onInsert(ResourceEventInterface $event): void
    {
        if (!$this->enabled) {
            return;
        }

        $member = $this->getMemberFromEvent($event);

        $audienceId = $member->getAudience()->getIdentifier();

        $result = $this->api->post("lists/$audienceId/members", [
            'email_address' => $member->getEmailAddress(),
            'status'        => $member->getStatus(),
        ]);

        if (!$this->api->success()) {
            throw new ApiException($result);
        }

        $member
            ->setIdentifier($result['id'])
            ->setWebIdentifier($result['web_id']);

        $this->persistenceHelper->persistAndRecompute($member, false);
    }

    /**
     * Update event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onUpdate(ResourceEventInterface $event): void
    {
        if (!$this->enabled) {
            return;
        }

        $member = $this->getMemberFromEvent($event);

        $map = $this->buildPatchMap($member, [
            'status'      => null,
            'mergeFields' => 'merge_fields',
        ]);

        if (empty($map)) {
            return;
        }

        $map['emailAddress'] = $member->getEmailAddress();

        $audienceId = $member->getAudience()->getIdentifier();
        $hash       = $this->api->subscriberHash($member->getEmailAddress());

        $result = $this->api->patch("lists/$audienceId/members/$hash", $map);

        if (!$this->api->success()) {
            throw new ApiException($result);
        }
    }

    /**
     * Delete event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onDelete(ResourceEventInterface $event): void
    {
        if (!$this->enabled) {
            return;
        }

        $member = $this->getMemberFromEvent($event);

        $audienceId = $member->getAudience()->getIdentifier();
        $hash       = $this->api->subscriberHash($member->getEmailAddress());

        $result = $this->api->delete("lists/$audienceId/members/$hash");

        if (!$this->api->success()) {
            throw new ApiException($result);
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
    public static function getSubscribedEvents()
    {
        return [
            MemberEvents::INSERT => ['onInsert', 0],
            MemberEvents::UPDATE => ['onUpdate', 0],
            MemberEvents::DELETE => ['onDelete', 0],
        ];
    }
}
