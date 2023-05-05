<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Ad;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class AdRepository
{
    /**
     * @var EntityRepository<Ad>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(Ad::class);
    }

    /**
     * @return array<Ad>
     */
    public function findAllByAccountId(int $userId): array
    {
        return $this->repository->findBy(['userId' => $userId]);
    }

    public function findByUserIdAndExternalId(int $userId, string $externalId): ?Ad
    {
        return $this->repository->findOneBy(['userId' => $userId, 'externalId' => $externalId]);
    }

    /**
     * @return array<Ad>
     */
    public function findAllByAdSetId(int $adSetId): array
    {
        return $this->repository->findBy(['adSetId' => $adSetId], ['startedAt' => 'ASC', 'id' => 'ASC']);
    }

    /**
     * @return array<Ad>
     */
    public function findAllByIds(array $ids): array
    {
        return $this->repository->findBy(['id' => $ids], ['startedAt' => 'ASC', 'id' => 'ASC']);
    }

    public function save(Ad $ad): void
    {
        $this->em->persist($ad);
        $this->em->flush();
    }
}
