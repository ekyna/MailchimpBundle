<?php

namespace Ekyna\Bundle\MailchimpBundle\Repository;

use Ekyna\Bundle\MailchimpBundle\Model\AudienceInterface;
use Ekyna\Bundle\MailchimpBundle\Model\MemberInterface;
use Ekyna\Component\Resource\Doctrine\ORM\ResourceRepository;

/**
 * Class MemberRepository
 * @package Ekyna\Bundle\MailchimpBundle\Repository
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class MemberRepository extends ResourceRepository
{
    /**
     * @inheritDoc
     */
    public function createNew()
    {
        /** @var MemberInterface $member */
        $member = parent::createNew();

        $member->setMergeFields([
            'FNAME'    => null,
            'LNAME'    => null,
            'BIRTHDAY' => null,
        ]);

        return $member;
    }

    /**
     * Finds one member by its (mailchimp) identifier.
     *
     * @param string $id
     *
     * @return MemberInterface|null
     */
    public function findOneByIdentifier(string $id): ?MemberInterface
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->findOneBy(['identifier' => $id]);
    }

    /**
     * Finds one member by its (mailchimp) web identifier.
     *
     * @param string $id
     *
     * @return MemberInterface|null
     */
    public function findOneByWebIdentifier(string $id): ?MemberInterface
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->findOneBy(['webIdentifier' => $id]);
    }

    /**
     * Finds the member by audience and email address.
     *
     * @param AudienceInterface $audience
     * @param string            $email
     *
     * @return MemberInterface|null
     */
    public function findOneByAudienceAndEmail(AudienceInterface $audience, string $email): ?MemberInterface
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->findOneBy([
            'audience'     => $audience,
            'emailAddress' => $email,
        ]);
    }

    /**
     * Finds members having an identifier not in the given ones.
     *
     * @param array $identifiers
     *
     * @return MemberInterface[]
     */
    public function findByNotIds(array $identifiers): array
    {
        if (empty($identifiers)) {
            return [];
        }

        $qb = $this->createQueryBuilder('m');

        return $qb
            ->andWhere($qb->expr()->notIn('m.identifier', ':identifiers'))
            ->getQuery()
            ->setParameter('identifiers', $identifiers)
            ->getResult();
    }
}
