<?php

namespace Ekyna\Bundle\MailchimpBundle\Event;

/**
 * Class MemberEvents
 * @package Ekyna\Bundle\MailchimpBundle\Event
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
final class MemberEvents
{
    // Persistence
    const INSERT      = 'ekyna_mailchimp.member.insert';
    const UPDATE      = 'ekyna_mailchimp.member.update';
    const DELETE      = 'ekyna_mailchimp.member.delete';

    // Domain
    const INITIALIZE  = 'ekyna_mailchimp.member.initialize';

    const PRE_CREATE  = 'ekyna_mailchimp.member.pre_create';
    const POST_CREATE = 'ekyna_mailchimp.member.post_create';

    const PRE_UPDATE  = 'ekyna_mailchimp.member.pre_update';
    const POST_UPDATE = 'ekyna_mailchimp.member.post_update';

    const PRE_DELETE  = 'ekyna_mailchimp.member.pre_delete';
    const POST_DELETE = 'ekyna_mailchimp.member.post_delete';


    /**
     * Disabled constructor.
     */
    private function __construct()
    {
    }
}
