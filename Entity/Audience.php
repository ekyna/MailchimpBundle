<?php

namespace Ekyna\Bundle\MailchimpBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ekyna\Bundle\MailchimpBundle\Model\AudienceInterface;
use Ekyna\Component\Commerce\Customer\Model\CustomerGroupInterface;

/**
 * Class Audience
 * @package Ekyna\Bundle\MailchimpBundle\Entity
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class Audience implements AudienceInterface
{
    use ObjectTrait;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var bool
     */
    private $webhook = false;

    /**
     * @var Collection|CustomerGroupInterface[]
     */
    private $groups;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): AudienceInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * @inheritDoc
     */
    public function setSecret(string $secret): AudienceInterface
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isWebhook(): bool
    {
        return $this->webhook;
    }

    /**
     * @inheritDoc
     */
    public function setWebhook(bool $webhook): AudienceInterface
    {
        $this->webhook = $webhook;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addGroup(CustomerGroupInterface $group): AudienceInterface
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function removeGroup(CustomerGroupInterface $group): AudienceInterface
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }
}
