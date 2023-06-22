<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AdSet;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Uid\Ulid;

class AdSetRepository
{
    /**
     * @var EntityRepository<AdSet>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(AdSet::class);
    }

    /**
     * @return array<AdSet>
     */
    public function findAllByAccountId(Ulid $userId): array
    {
        return $this->repository->findBy(['userId' => $userId]);
    }

    /**
     * @return array<AdSet>
     */
    public function findAllByCampaignId(Ulid $campaignId): array
    {
        return $this->repository->findBy(['campaignId' => $campaignId], ['startedAt' => 'ASC']);
    }

    public function findByAccountIdAndExternalId(Ulid $accountId, mixed $externalId): ?AdSet
    {
        return $this->repository->findOneBy([
            'accountId' => $accountId,
            'externalId' => $externalId,
        ]);
    }

    public function findByCampaignIdAndExternalId(Ulid $accountId, Ulid $campaignId, mixed $externalId): ?AdSet
    {
        return $this->repository->findOneBy([
            'accountId' => $accountId,
            'campaignId' => $campaignId,
            'externalId' => $externalId,
        ]);
    }

    /**
     * Returns a date when the fist ad was started and when last was ended within given ad set.
     *
     * @return array{'start': null|DateTimeImmutable, 'end': null|DateTimeImmutable}
     */
    public function findStartAndEndDate(int $adSetId): array
    {
        $query = <<<'QUERY'
            select MIN(aas."date") as start, MAX(aas."date") as end from ad_insights aas
            left join ad a on a.id = aas.ad_id
            where a.ad_set_id = :adSetId group by a.ad_set_id;
            QUERY;

        $stmt = $this->em->getConnection()->prepare($query);
        $stmt->bindValue('adSetId', $adSetId);
        $result = $stmt->executeQuery();

        $row = $result->fetchAssociative();
        $data = ['start' => null, 'end' => null];

        if ($row !== false && isset($row['start'], $row['end'])) {
            $data['start'] = DateTimeImmutable::createFromFormat('Y-m-d', $row['start']);
            $data['end'] = DateTimeImmutable::createFromFormat('Y-m-d', $row['end']);

            assert($data['start'] instanceof DateTimeImmutable);
            assert($data['end'] instanceof DateTimeImmutable);
        }

        return $data;
    }

    public function save(AdSet $adSet): void
    {
        $this->em->persist($adSet);
        $this->em->flush();
    }
}
