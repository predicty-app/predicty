<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Ad;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Uid\Ulid;

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
    public function findAllByAccountId(Ulid $userId): array
    {
        return $this->repository->findBy(['userId' => $userId]);
    }

    public function findByAccountIdAndExternalId(Ulid $userId, string $externalId): ?Ad
    {
        return $this->repository->findOneBy(['accountId' => $userId, 'externalId' => $externalId]);
    }

    /**
     * @return array<Ad>
     */
    public function findAllByAdSetId(Ulid $adSetId): array
    {
        return $this->repository->findBy(['adSetId' => $adSetId], ['startedAt' => 'ASC', 'id' => 'ASC']);
    }

    /**
     * @return array<Ad>
     */
    public function findAllByIds(array $ids): array
    {
        // it seems that arrays of Ulid's are not supported by doctrine
        $ids = array_map(fn (Ulid $id) => $id->toRfc4122(), $ids);

        return $this->repository->findBy(['id' => $ids], ['startedAt' => 'ASC', 'id' => 'ASC']);
    }

    public function save(Ad $ad): void
    {
        $this->em->persist($ad);
        $this->em->flush();
    }
}
