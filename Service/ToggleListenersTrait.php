<?php

namespace Ekyna\Bundle\MailchimpBundle\Service;

use Ekyna\Bundle\MailchimpBundle\Listener\AbstractListener;

/**
 * Trait ToggleListenersTrait
 * @package Ekyna\Bundle\MailchimpBundle\Service
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
trait ToggleListenersTrait
{
    /**
     * @var AbstractListener[]
     */
    private $listeners;


    /**
     * Adds the listener.
     *
     * @param AbstractListener $listener
     */
    private function addListener(AbstractListener $listener): void
    {
        $this->listeners[] = $listener;
    }

    /**
     * Disables listeners.
     */
    protected function disableListeners(): void
    {
        foreach ($this->listeners as $listener) {
            $listener->setEnabled(false);
        }
    }

    /**
     * Enables listeners.
     */
    protected function enableListeners(): void
    {
        foreach ($this->listeners as $listener) {
            $listener->setEnabled(true);
        }
    }
}
