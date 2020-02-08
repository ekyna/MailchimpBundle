<?php

namespace Ekyna\Bundle\MailchimpBundle\DependencyInjection;

use Ekyna\Bundle\MailchimpBundle\Service\Mailchimp;
use Ekyna\Bundle\ResourceBundle\DependencyInjection\AbstractExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class EkynaMailchimpExtension
 * @package Ekyna\Bundle\MailchimpBundle\DependencyInjection
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class EkynaMailchimpExtension extends AbstractExtension
{
    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->configure($configs, 'ekyna_mailchimp', new Configuration(), $container);

        $container
            ->getDefinition(Mailchimp::class)
            ->setArgument(0, $config['api']['key']);
    }
}
