<?php

namespace Ekyna\Bundle\MailchimpBundle\Listener;

use Ekyna\Bundle\MailchimpBundle\Service\Mailchimp;
use Ekyna\Component\Resource\Model\ResourceInterface;
use Ekyna\Component\Resource\Persistence\PersistenceHelperInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class AbstractListener
 * @package Ekyna\Bundle\MailchimpBundle\Listener
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractListener implements EventSubscriberInterface
{
    /**
     * @var PersistenceHelperInterface
     */
    protected $persistenceHelper;

    /**
     * @var Mailchimp
     */
    protected $api;

    /**
     * @var bool
     */
    protected $enabled = true;


    /**
     * Constructor.
     *
     * @param PersistenceHelperInterface $persistenceHelper
     * @param Mailchimp                  $api
     */
    public function __construct(PersistenceHelperInterface $persistenceHelper, Mailchimp $api)
    {
        $this->persistenceHelper = $persistenceHelper;
        $this->api               = $api;
    }

    /**
     * Sets whether the listener is enabled.
     *
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * Builds the patch map.
     *
     * @param ResourceInterface $resource
     * @param array             $properties
     *
     * @return array
     */
    protected function buildPatchMap(ResourceInterface $resource, array $properties): array
    {
        $map = [];

        $cs = $this->persistenceHelper->getChangeSet($resource);

        foreach ($properties as $property => $field) {
            $field = $field ?? $property;
            if (isset($cs[$property])) {
                $map[$field] = $cs[$property][1];
            }
        }

        return $map;
    }
}
