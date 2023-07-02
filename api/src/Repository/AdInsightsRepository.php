<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AdInsights;
use App\Entity\DailyInsights;
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
class AdInsightsRepository
{
    /**
     * @var EntityRepository<AdInsights>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(AdInsights::class);
    }

    public function findByAdIdAndDate(Ulid $adId, DateTimeInterface $date): ?AdInsights
    {
        return $this->repository->findOneBy(['adId' => $adId, 'date' => $date]);
    }

    /**
     * @return array<AdInsights>
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
        $query = 'select MIN(ads."date") as start, MAX(ads."date") as end from ad_insights ads where ads.ad_id = :adId';
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
            select MIN(stats."date") as start, MAX(stats."date") as end from ad_insights stats
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
            select MIN(stats."date") as start, MAX(stats."date") as end from ad_insights stats
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
            select MIN(stats."date") as start, MAX(stats."date") as end from ad_insights stats
            where stats.ad_id in ($placeholder);
            QUERY;

        $stmt = $this->em->getConnection()->prepare($query);
        foreach ($adsIds as $key => $adsId) {
            $stmt->bindValue($key + 1, $adsId->toRfc4122(), ParameterType::STRING);
        }

        return $this->fetchStartAndEndDate($stmt);
    }

    /**
     * @return iterable<DailyInsights>
     */
    public function getDailyAggregatedStats(Ulid $accountId, DateTimeImmutable $since, DateTimeImmutable $to): iterable
    {
        // note that the " is required for postgres, for camel case column aliases - otherwise it will all be lowercase
        // @see https://github.com/MagicStack/asyncpg/issues/402
        // @see https://www.postgresql.org/docs/current/sql-syntax-lexical.html#SQL-SYNTAX-IDENTIFIERS
        $query = <<<'QUERY'
            select
            ai.date as date,
            sum(ai.conversions) as results,
            sum(ai.amount_spent) as "amountSpent",
            sum(dr.revenue) as revenue,
            avg(dr.average_order_value)::numeric(10,0) as "averageOrderValue",
            sum(ai.leads) as leads,
            sum(ai.clicks) as clicks,
            sum(ai.impressions) as impressions
            from ad_insights ai
            left join daily_revenue dr on ai."date" = dr."date"
            where ai."date" >= :since and ai."date" <= :to
            group by ai.ad_id, ai."date"
            order by ai.ad_id, ai."date";
            QUERY;

        $stmt = $this->em->getConnection()->prepare($query);
        $stmt->bindValue('since', $since->format('Y-m-d'), ParameterType::STRING);
        $stmt->bindValue('to', $to->format('Y-m-d'), ParameterType::STRING);
        $result = $stmt->executeQuery();

        $currency = 'PLN';
        while (($row = $result->fetchAssociative()) !== false) {
            yield DailyInsights::fromScalar(
                $row['date'],
                $row['results'],
                $row['clicks'],
                $row['impressions'],
                $row['leads'],
                (int) $row['averageOrderValue'],
                $row['amountSpent'],
                (int) $row['revenue'],
                $currency
            );
        }
    }

    public function save(AdInsights $entity): void
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
