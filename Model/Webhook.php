<?php

namespace Ekyna\Bundle\MailchimpBundle\Model;

/**
 * Class Webhook
 * @package Ekyna\Bundle\MailchimpBundle\Model
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class Webhook
{
    public const TYPE_SUBSCRIBE   = 'subscribe';
    public const TYPE_UNSUBSCRIBE = 'unsubscribe';
    public const TYPE_PROFILE     = 'profile';
    public const TYPE_CLEANED     = 'cleaned';
    public const TYPE_UPEMAIL     = 'upemail';
    public const TYPE_CAMPAIGN    = 'campaign';

    public const SOURCE_USER  = 'user';
    public const SOURCE_ADMIN = 'admin';
    public const SOURCE_API   = 'api';


    /**
     * Returns all the webhook types.
     *
     * @return array
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_SUBSCRIBE,
            self::TYPE_UNSUBSCRIBE,
            self::TYPE_PROFILE,
            self::TYPE_CLEANED,
            self::TYPE_UPEMAIL,
            self::TYPE_CAMPAIGN,
        ];
    }

    /**
     * Returns all the webhook sources.
     *
     * @return array
     */
    public static function getSources(): array
    {
        return [
            self::SOURCE_USER,
            self::SOURCE_ADMIN,
            self::SOURCE_API,
        ];
    }

    /**
     * Disabled constructor.
     */
    private function __construct()
    {
    }
}
