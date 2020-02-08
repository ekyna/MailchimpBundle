<?php

namespace Ekyna\Bundle\MailchimpBundle\Model;

/**
 * Class ObjectInterface
 * @package Ekyna\Bundle\MailchimpBundle\Model
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
interface ObjectInterface
{
    /**
     * Returns the identifier.
     *
     * @return string
     */
    public function getIdentifier(): ?string;

    /**
     * Sets the identifier.
     *
     * @param string $identifier
     *
     * @return $this|ObjectInterface
     */
    public function setIdentifier(string $identifier): ObjectInterface;

    /**
     * Returns the web identifier.
     *
     * @return string
     */
    public function getWebIdentifier(): ?string;

    /**
     * Sets the web identifier.
     *
     * @param string $identifier
     *
     * @return $this|ObjectInterface
     */
    public function setWebIdentifier(string $identifier): ObjectInterface;
}
