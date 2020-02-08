<?php

namespace Ekyna\Bundle\MailchimpBundle\Repository;

use Ekyna\Bundle\MailchimpBundle\Model\AudienceInterface;
use Ekyna\Component\Commerce\Customer\Model\CustomerGroupInterface;
use Ekyna\Component\Resource\Doctrine\ORM\ResourceRepository;

/**
 * Class AudienceRepository
 * @package Ekyna\Bundle\MailchimpBundle\Repository
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class AudienceRepository extends ResourceRepository
{
    /**
     * Finds one audience by its (mailchimp) identifier.
     *
     * @param string $id
     *
     * @return AudienceInterface|null
     */
    public function findOneByIdentifier(string $id): ?AudienceInterface
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->findOneBy(['identifier' => $id]);
    }

    /**
     * Finds one audience by its (mailchimp) identifier and secret.
     *
     * @param string $id
     * @param string $secret
     *
     * @return AudienceInterface|null
     */
    public function findOneByIdentifierAndSecret(string $id, string $secret): ?AudienceInterface
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->findOneBy([
            'identifier' => $id,
            'secret'     => $secret,
        ]);
    }

    /**
     * Finds audiences whose webhook is not configured.
     *
     * @return AudienceInterface[]
     */
    public function findWithWebhookNotConfigured(): array
    {
        $qb = $this->createQueryBuilder('a');

        return $qb
            ->andWhere($qb->expr()->eq('a.webhook', ':webhook'))
            ->andWhere($qb->expr()->isNotNull('a.secret'))
            ->andWhere($qb->expr()->isNotNull('a.identifier'))
            ->andWhere($qb->expr()->isNotNull('a.webIdentifier'))
            ->getQuery()
            ->setParameter('webhook', false)
            ->getResult();
    }

    /**
     * Finds audiences having an identifier not in the given ones.
     *
     * @param array $identifiers
     *
     * @return AudienceInterface[]
     */
    public function findByNotIds(array $identifiers): array
    {
        if (empty($identifiers)) {
            return [];
        }

        $qb = $this->createQueryBuilder('a');

        return $qb
            ->andWhere($qb->expr()->notIn('a.identifier', ':identifiers'))
            ->getQuery()
            ->setParameter('identifiers', $identifiers)
            ->getResult();
    }

    /**
     * Finds audiences by customer groups.
     *
     * @param CustomerGroupInterface $group
     *
     * @return AudienceInterface[]
     */
    public function findByCustomerGroup(CustomerGroupInterface $group)
    {
        $qb = $this->createQueryBuilder('a');

        return $qb
            ->andWhere($qb->expr()->isMemberOf(':group', 'a.groups'))
            ->getQuery()
            ->setParameter('group', $group)
            ->getResult();
    }
}
