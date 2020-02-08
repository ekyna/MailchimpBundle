<?php

namespace Ekyna\Bundle\MailchimpBundle\Command;

use Ekyna\Bundle\MailchimpBundle\Service\Synchronizer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SynchronizeCommand
 * @package Ekyna\Bundle\MailchimpBundle\Command
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class SynchronizeCommand extends Command
{
    protected static $defaultName = 'ekyna:mailchimp:synchronize';

    /**
     * @var Synchronizer
     */
    private $synchronizer;


    /**
     * Constructor.
     *
     * @param Synchronizer $synchronizer
     */
    public function __construct(Synchronizer $synchronizer)
    {
        parent::__construct();

        $this->synchronizer = $synchronizer;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->synchronizer->setLogger(new ConsoleLogger($output));
        $this->synchronizer->synchronize();

        return 0;
    }
}
