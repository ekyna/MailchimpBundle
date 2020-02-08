<?php

namespace Ekyna\Bundle\MailchimpBundle\Service;

use DrewM\MailChimp\MailChimp as Base;
use Ekyna\Bundle\MailchimpBundle\Model\AudienceInterface;

/**
 * Class Mailchimp
 * @package Ekyna\Bundle\MailchimpBundle\Service
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class Mailchimp extends Base
{
    /**
     * Returns the lists.
     *
     * @return array
     */
    public function getLists(): array
    {
        if ($results = $this->get("lists")) {
            return $results['lists'];
        }

        return [];
    }

    /**
     * Returns the list's members.
     *
     * @param AudienceInterface $audience
     *
     * @return array
     */
    public function getAudienceMembers(AudienceInterface $audience): array
    {
        if ($results = $this->get("lists/{$audience->getIdentifier()}/members")) {
            return $results['members'];
        }

        return [];
    }
}
