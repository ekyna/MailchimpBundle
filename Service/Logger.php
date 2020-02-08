<?php

namespace Ekyna\Bundle\MailchimpBundle\Service;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

/**
 * Class Logger
 * @package Ekyna\Bundle\MailchimpBundle\Service
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class Logger extends AbstractLogger
{
    /**
     * @var LoggerInterface
     */
    private $logger;


    /**
     * Constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = [])
    {
        $this->logger->log($level, '[Mailchimp] ' . $message, $context);
    }
}
