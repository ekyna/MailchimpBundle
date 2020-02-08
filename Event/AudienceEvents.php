<?php

namespace Ekyna\Bundle\MailchimpBundle\Event;

/**
 * Class AudienceEvents
 * @package Ekyna\Bundle\MailchimpBundle\Event
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
final class AudienceEvents
{
    // Persistence
    const INSERT      = 'ekyna_mailchimp.audience.insert';
    const UPDATE      = 'ekyna_mailchimp.audience.update';
    const DELETE      = 'ekyna_mailchimp.audience.delete';

    // Domain
    const INITIALIZE  = 'ekyna_mailchimp.audience.initialize';

    const PRE_CREATE  = 'ekyna_mailchimp.audience.pre_create';
    const POST_CREATE = 'ekyna_mailchimp.audience.post_create';

    const PRE_UPDATE  = 'ekyna_mailchimp.audience.pre_update';
    const POST_UPDATE = 'ekyna_mailchimp.audience.post_update';

    const PRE_DELETE  = 'ekyna_mailchimp.audience.pre_delete';
    const POST_DELETE = 'ekyna_mailchimp.audience.post_delete';


    /**
     * Disabled constructor.
     */
    private function __construct()
    {
    }
}
