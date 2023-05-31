<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AdStats;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Statement;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Uid\Ulid;

/**
 * @phpstan-type DatePeriod array{start: null|DateTimeImmutable, end: null|DateTimeImmutable}
 */
class AdStatsRepository
{
    /**
     * @var EntityRepository<AdStats>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(AdStats::class);
    }

    public function findByAdIdAndDay(Ulid $adId, DateTimeInterface $day): ?AdStats
    {
        return $this->repository->findOneBy(['adId' => $adId, 'date' => $day]);
    }

    /**
     * @return array<AdStats>
     */
    public function findAllByAdId(Ulid $adId): array
    {
        return $this->repository->findBy(['adId' => $adId]);
    }

    /**
     * @return DatePeriod
     */
    public function findStartAndEndDateForAnAd(Ulid $adId): array
    {
        $query = 'select MIN(ads."date") as start, MAX(ads."date") as end from ad_stats ads where ads.ad_id = :adId';
        $stmt = $this->em->getConnection()->prepare($query);
        $stmt->bindValue('adId', $adId->toRfc4122(), ParameterType::STRING);

        return $this->fetchStartAndEndDate($stmt);
    }

    /**
     * @return DatePeriod
     */
    public function findStartAndEndDateForAnAdSet(Ulid $adSetId): array
    {
        $query = <<<'QUERY'
            select MIN(stats."date") as start, MAX(stats."date") as end from ad_stats stats
            left join ad a on a.id = stats.ad_id
            where a.ad_set_id = :adSetId group by a.ad_set_id;
            QUERY;

        $stmt = $this->em->getConnection()->prepare($query);
        $stmt->bindValue('adSetId', $adSetId->toRfc4122(), ParameterType::STRING);

        return $this->fetchStartAndEndDate($stmt);
    }

    /**
     * @return DatePeriod
     */
    public function findStartAndEndDateForACampaign(Ulid $campaignId): array
    {
        $query = <<<'QUERY'
            select MIN(stats."date") as start, MAX(stats."date") as end from ad_stats stats
            left join ad a on a.id = stats.ad_id
            left join ad_set aset on aset.id = a.ad_set_id
            where aset.campaign_id = :campaignId group by aset.campaign_id;
            QUERY;

        $stmt = $this->em->getConnection()->prepare($query);
        $stmt->bindValue('campaignId', $campaignId->toRfc4122(), ParameterType::STRING);

        return $this->fetchStartAndEndDate($stmt);
    }

    /**
     * @param array<Ulid> $adsIds
     *
     * @return DatePeriod
     */
    public function findStartAndEndDateForAnAdCollection(array $adsIds): array
    {
        if ($adsIds === []) {
            return ['start' => null, 'end' => null];
        }

        $placeholder = str_repeat('?,', count($adsIds) - 1).'?';
        $query = <<<"QUERY"
            select MIN(stats."date") as start, MAX(stats."date") as end from ad_stats stats
            where stats.ad_id in ($placeholder);
            QUERY;

        $stmt = $this->em->getConnection()->prepare($query);
        foreach ($adsIds as $key => $adsId) {
            $stmt->bindValue($key + 1, $adsId->toRfc4122(), ParameterType::STRING);
        }

        return $this->fetchStartAndEndDate($stmt);
    }

    public function save(AdStats $entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    /**
     * @return DatePeriod
     */
    private function fetchStartAndEndDate(Statement $statement): array
    {
        $result = $statement->executeQuery();
        $row = $result->fetchAssociative();
        $data = ['start' => null, 'end' => null];

        if ($row !== false && isset($row['start'], $row['end'])) {
            $data['start'] = DateTimeImmutable::createFromFormat('Y-m-d', $row['start']);
            $data['end'] = DateTimeImmutable::createFromFormat('Y-m-d', $row['end']);

            assert($data['start'] instanceof DateTimeImmutable);
            assert($data['end'] instanceof DateTimeImmutable);

            $data['start'] = $data['start']->setTime(0, 0, 0);
            $data['end'] = $data['end']->setTime(23, 59, 59);
        }

        return $data;
    }
}
