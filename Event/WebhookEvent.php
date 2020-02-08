<?php

namespace Ekyna\Bundle\MailchimpBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class WebhookEvent
 * @package Ekyna\Bundle\MailchimpBundle\Event
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class WebhookEvent extends Event
{
    /** Event triggered when received webhook for user subscribe */
    public const SUBSCRIBE = 'ekyna_mailchimp.webhook.subscribe';

    /** Event triggered when received webhook for user unsubscribe */
    public const UNSUBSCRIBE = 'ekyna_mailchimp.webhook.unsubscribe';

    /** Event triggered when received webhook for user update profile */
    public const PROFILE = 'ekyna_mailchimp.webhook.profile';

    /** Event triggered when received webhook for user cleaned */
    public const CLEANED = 'ekyna_mailchimp.webhook.cleaned';

    /** Event triggered when received webhook for user update email [legacy?] */
    public const UPEMAIL = 'ekyna_mailchimp.webhook.upemail';

    /** Event triggered when received webhook for new campaign send */
    public const CAMPAIGN = 'ekyna_mailchimp.webhook.campaign';


    /**
     * @var array
     */
    private $data;


    /**
     * Constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Returns the event data.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
