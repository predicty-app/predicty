<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\DataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class DataProviderRepository
{
    /**
     * @var EntityRepository<DataProvider>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(DataProvider::class);
    }

    /**
     * @return array<DataProvider>
     */
    public function findAllForUser(int $userId): array
    {
        return $this->repository->findBy(['userId' => [null, $userId]]);
    }

    public function save(DataProvider $entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    /**
     * @return array<DataProvider>
     */
    public function findAllForEverybody(): array
    {
        return $this->repository->findBy(['userId' => [null]]);
    }

    public function findById(int $id): ?DataProvider
    {
        return $this->repository->find($id);
    }
}
