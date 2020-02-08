<?php

namespace Ekyna\Bundle\MailchimpBundle\Model;

use Doctrine\Common\Collections\Collection;
use Ekyna\Bundle\MailchimpBundle\Entity\Audience;
use Ekyna\Component\Commerce\Customer\Model\CustomerGroupInterface;
use Ekyna\Component\Resource\Model\ResourceInterface;

/**
 * Interface AudienceInterface
 * @package Ekyna\Bundle\MailchimpBundle\Model
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
interface AudienceInterface extends ObjectInterface, ResourceInterface
{
    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName(): ?string;

    /**
     * Sets the name.
     *
     * @param string $name
     *
     * @return $this|AudienceInterface
     */
    public function setName(string $name): AudienceInterface;

    /**
     * Returns the secret.
     *
     * @return string
     */
    public function getSecret(): string;

    /**
     * Sets the secret.
     *
     * @param string $secret
     *
     * @return $this|AudienceInterface
     */
    public function setSecret(string $secret): AudienceInterface;

    /**
     * Returns whether the webhook has been configured.
     *
     * @return bool
     */
    public function isWebhook(): bool;

    /**
     * Sets whether the webhook has been configured.
     *
     * @param bool $webhook
     *
     * @return $this|AudienceInterface
     */
    public function setWebhook(bool $webhook): AudienceInterface;

    /**
     * Adds the customer group.
     *
     * @param CustomerGroupInterface $group
     *
     * @return $this|AudienceInterface
     */
    public function addGroup(CustomerGroupInterface $group): AudienceInterface;

    /**
     * Removes the customer group.
     *
     * @param CustomerGroupInterface $group
     *
     * @return $this|AudienceInterface
     */
    public function removeGroup(CustomerGroupInterface $group): AudienceInterface;

    /**
     * Returns the customer groups.
     *
     * @return Collection|CustomerGroupInterface[]
     */
    public function getGroups(): Collection;
}
