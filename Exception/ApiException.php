<?php

namespace Ekyna\Bundle\MailchimpBundle\Exception;

/**
 * Class ApiException
 * @package Ekyna\Bundle\MailchimpBundle\Exception
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class ApiException extends \RuntimeException implements ExceptionInterface
{
    /**
     * @inheritDoc
     */
    public function __construct(array $apiResult)
    {
        $message = $apiResult['title'] . "\n" . $apiResult['detail'];

        parent::__construct($message);
    }
}
