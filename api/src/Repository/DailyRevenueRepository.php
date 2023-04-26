<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AdStats;
use App\Entity\DailyRevenue;
use Brick\Money\Money;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class DailyRevenueRepository
{
    /**
     * @var EntityRepository<DailyRevenue>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(DailyRevenue::class);
    }

    public function findByDay(int $userId, DateTimeInterface $date): ?DailyRevenue
    {
        return $this->repository->findOneBy(['userId' => $userId, 'date' => $date]);
    }

    public function getDailyRevenueFor(AdStats $adStats): Money
    {
        $revenue = $this->findByDay($adStats->getUserId(), $adStats->getDate());

        if ($revenue === null) {
            return Money::of(0, $adStats->getCurrency());
        }

        return $revenue->getAverageOrderValue()->multipliedBy($adStats->getResults());
    }

    /**
     * @return array<DailyRevenue>
     */
    public function findAll(): array
    {
        return $this->repository->findBy([], ['date' => 'DESC']);
    }

    /**
     * @return array<DailyRevenue>
     */
    public function findAllByUserId(int $userId): array
    {
        return $this->repository->findBy(['userId' => $userId], ['date' => 'DESC']);
    }

    public function save(DailyRevenue $revenue): void
    {
        $this->persist($revenue);
        $this->flush();
    }

    public function persist(DailyRevenue $revenue): void
    {
        $this->em->persist($revenue);
    }

    public function flush(): void
    {
        $this->em->flush();
    }
}
