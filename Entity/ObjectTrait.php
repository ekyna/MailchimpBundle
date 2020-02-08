<?php

namespace Ekyna\Bundle\MailchimpBundle\Entity;

use Ekyna\Bundle\MailchimpBundle\Model\ObjectInterface;

/**
 * Trait ObjectTrait
 * @package Ekyna\Bundle\MailchimpBundle\Entity
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
trait ObjectTrait
{
    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $webIdentifier;


    /**
     * Returns the identifier.
     *
     * @return string
     */
    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    /**
     * Sets the identifier.
     *
     * @param string $identifier
     *
     * @return $this|ObjectInterface
     */
    public function setIdentifier(string $identifier): ObjectInterface
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Returns the web identifier.
     *
     * @return string
     */
    public function getWebIdentifier(): ?string
    {
        return $this->webIdentifier;
    }

    /**
     * Sets the web identifier.
     *
     * @param string $identifier
     *
     * @return $this|ObjectInterface
     */
    public function setWebIdentifier(string $identifier): ObjectInterface
    {
        $this->webIdentifier = $identifier;

        return $this;
    }
}
