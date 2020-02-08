<?php

namespace Ekyna\Bundle\MailchimpBundle\Entity;

use Ekyna\Bundle\MailchimpBundle\Model\AudienceInterface;
use Ekyna\Bundle\MailchimpBundle\Model\MemberInterface;

/**
 * Class Member
 * @package Ekyna\Bundle\MailchimpBundle\Entity
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class Member implements MemberInterface
{
    use ObjectTrait;

    /**
     * @var int
     */
    private $id;

    /**
     * @var AudienceInterface
     */
    protected $audience;

    /**
     * @var string
     */
    protected $emailAddress;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var array
     */
    protected $mergeFields = [];

    // TODO more fields

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        // TODO Audience name ?

        return $this->emailAddress;
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
    public function getAudience(): ?AudienceInterface
    {
        return $this->audience;
    }

    /**
     * @inheritDoc
     */
    public function setAudience(AudienceInterface $audience): MemberInterface
    {
        $this->audience = $audience;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    /**
     * @inheritDoc
     */
    public function setEmailAddress(string $address): MemberInterface
    {
        $this->emailAddress = $address;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @inheritDoc
     */
    public function setStatus(string $status): MemberInterface
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMergeFields(): array
    {
        return $this->mergeFields;
    }

    /**
     * @inheritDoc
     */
    public function setMergeFields(array $data): MemberInterface
    {
        $this->mergeFields = $data;

        return $this;
    }
}
