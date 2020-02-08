<?php

namespace Ekyna\Bundle\MailchimpBundle\Model;

use Ekyna\Bundle\ResourceBundle\Model\AbstractConstants;

/**
 * Class Status
 * @package Ekyna\Bundle\MailchimpBundle\Model
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
final class MemberStatuses extends AbstractConstants
{
    public const SUBSCRIBED    = 'subscribed';
    public const UNSUBSCRIBED  = 'unsubscribed';
    public const CLEANED       = 'cleaned';
    public const PENDING       = 'pending';
    public const TRANSACTIONAL = 'transactional';


    /**
     * @inheritDoc
     */
    public static function getConfig(): array
    {
        $prefix = 'ekyna_mailchimp.status.';

        return [
            self::SUBSCRIBED    => [$prefix . self::SUBSCRIBED,    'success'],
            self::UNSUBSCRIBED  => [$prefix . self::UNSUBSCRIBED,  'danger'],
            self::CLEANED       => [$prefix . self::CLEANED,       'default'],
            self::PENDING       => [$prefix . self::PENDING,       'warning'],
            self::TRANSACTIONAL => [$prefix . self::TRANSACTIONAL, 'info'],
        ];
    }
}
