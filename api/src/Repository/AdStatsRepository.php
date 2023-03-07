<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AdStats;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

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

    public function save(AdStats $entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function findByAdIdAndDay(int $adId, \DateTimeInterface $day): ?AdStats
    {
        return $this->repository->findOneBy(['adId' => $adId, 'date' => $day]);
    }
}
