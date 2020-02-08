<?php

namespace Ekyna\Bundle\MailchimpBundle\Listener;

use Ekyna\Bundle\MailchimpBundle\Event\AudienceEvents;
use Ekyna\Bundle\MailchimpBundle\Exception\ApiException;
use Ekyna\Bundle\MailchimpBundle\Model\AudienceInterface;
use Ekyna\Component\Resource\Event\ResourceEventInterface;
use Ekyna\Component\Resource\Exception\RuntimeException;
use Ekyna\Component\Resource\Exception\UnexpectedTypeException;

/**
 * Class AudienceListener
 * @package Ekyna\Bundle\MailchimpBundle\Listener
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class AudienceListener extends AbstractListener
{
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

        $audience = $this->getAudienceFromEvent($event);

        $map = $this->buildPatchMap($audience, [
            'name' => null,
        ]);

        if (empty($map)) {
            return;
        }

        $result = $this->api->patch("lists/{$audience->getIdentifier()}", $map);

        if (!$this->api->success()) {
            throw new ApiException($result);
        }
    }

    /**
     * Pre create event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onPreCreate(ResourceEventInterface $event): void
    {
        if (!$this->enabled) {
            return;
        }

        $audience = $this->getAudienceFromEvent($event);

        if (empty($audience->getIdentifier())) {
            throw new RuntimeException("You can't create audience with this administration. Use Mailchimp website.");
        }
    }

    /**
     * Pre create event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onPreDelete(ResourceEventInterface $event): void
    {
        if (!$this->enabled) {
            return;
        }

        $audience = $this->getAudienceFromEvent($event);

        if (empty($audience->getIdentifier())) {
            throw new RuntimeException("You can't delete audience from this administration. Use Mailchimp website.");
        }
    }

    /**
     * Returns the audience from the event.
     *
     * @param ResourceEventInterface $event
     *
     * @return AudienceInterface
     */
    protected function getAudienceFromEvent(ResourceEventInterface $event): AudienceInterface
    {
        $audience = $event->getResource();

        if (!$audience instanceof AudienceInterface) {
            throw new UnexpectedTypeException($audience, AudienceInterface::class);
        }

        return $audience;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            AudienceEvents::UPDATE     => ['onUpdate', 0],
            AudienceEvents::PRE_CREATE => ['onPreDelete', 0],
            AudienceEvents::PRE_DELETE => ['onPreDelete', 0],
        ];
    }
}
