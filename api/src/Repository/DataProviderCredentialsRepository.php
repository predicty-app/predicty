<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\DataProvider;
use App\Entity\DataProviderCredentials;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class DataProviderCredentialsRepository
{
    /**
     * @var EntityRepository<DataProviderCredentials>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $this->em->getRepository(DataProviderCredentials::class);
    }

    public function findOrCreate(int $userId, DataProvider $type): DataProviderCredentials
    {
        return $this->find($userId, $type) ?? new DataProviderCredentials($userId, $type);
    }

    public function find(int $userId, DataProvider $type): ?DataProviderCredentials
    {
        return $this->repository->findOneBy(['userId' => $userId, 'dataProvider' => $type]);
    }

    public function save(DataProviderCredentials $entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
}
