<?php

namespace Ekyna\Bundle\MailchimpBundle\Model;

use Ekyna\Component\Resource\Model\ResourceInterface;

/**
 * Interface MemberInterface
 * @package Ekyna\Bundle\MailchimpBundle\Model
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
interface MemberInterface extends ObjectInterface, ResourceInterface
{
    /**
     * Returns the audience.
     *
     * @return AudienceInterface
     */
    public function getAudience(): ?AudienceInterface;

    /**
     * Sets the audience.
     *
     * @param AudienceInterface $audience
     *
     * @return $this|MemberInterface
     */
    public function setAudience(AudienceInterface $audience): MemberInterface;

    /**
     * Returns the email address.
     *
     * @return string
     */
    public function getEmailAddress(): ?string;

    /**
     * Sets the email address.
     *
     * @param string $address
     *
     * @return $this|MemberInterface
     */
    public function setEmailAddress(string $address): MemberInterface;

    /**
     * Returns the status.
     *
     * @return string
     *
     * @see \Ekyna\Bundle\MailchimpBundle\Model\MemberStatuses
     */
    public function getStatus(): ?string;

    /**
     * Sets the status.
     *
     * @param string $status
     *
     * @return $this|MemberInterface
     *
     * @see \Ekyna\Bundle\MailchimpBundle\Model\MemberStatuses
     */
    public function setStatus(string $status): MemberInterface;

    /**
     * Returns the merge fields.
     *
     * @return array
     */
    public function getMergeFields(): array;

    /**
     * Sets the merge fields.
     *
     * @param array $merges
     *
     * @return $this|MemberInterface
     */
    public function setMergeFields(array $merges): MemberInterface;
}
