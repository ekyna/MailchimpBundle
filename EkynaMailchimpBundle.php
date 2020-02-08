<?php

namespace Ekyna\Bundle\MailchimpBundle;

use Ekyna\Bundle\MailchimpBundle\Model\AudienceInterface;
use Ekyna\Bundle\MailchimpBundle\Model\MemberInterface;
use Ekyna\Bundle\ResourceBundle\AbstractBundle;

/**
 * Class EkynaMailchimpBundle
 * @package Ekyna\Bundle\MailchimpBundle
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class EkynaMailchimpBundle extends AbstractBundle
{
    /**
     * @inheritDoc
     */
    protected function getModelInterfaces()
    {
        return [
            AudienceInterface::class => 'ekyna_mailchimp.audience.class',
            MemberInterface::class   => 'ekyna_mailchimp.member.class',
        ];
    }
}
