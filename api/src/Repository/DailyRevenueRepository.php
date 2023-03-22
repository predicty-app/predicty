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

    public function findByDay(DateTimeInterface $date): ?DailyRevenue
    {
        return $this->repository->findOneBy(['date' => $date]);
    }

    public function getDailyRevenueFor(AdStats $adStats): Money
    {
        $revenue = $this->findByDay($adStats->getDate());

        if ($revenue === null) {
            return Money::of(0, $adStats->getCurrency());
        }

        return $revenue->getAverageOrderValue()->multipliedBy($adStats->getResults());
    }

    public function save(DailyRevenue $campaign): void
    {
        $this->em->persist($campaign);
        $this->em->flush();
    }

    /**
     * @return array<DailyRevenue>
     */
    public function findAll(): array
    {
        return $this->repository->findBy([], ['date' => 'DESC']);
    }
}
