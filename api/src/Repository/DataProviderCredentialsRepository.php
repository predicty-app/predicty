<?php

declare(strict_types=1);

namespace App\Repository;

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

    public function findOrCreate(int $userId, int $dataProviderId): DataProviderCredentials
    {
        $entity = $this->repository->findOneBy(['userId' => $userId, 'dataProviderId' => $dataProviderId]);

        if ($entity === null) {
            $entity = new DataProviderCredentials($userId, $dataProviderId);
        }

        return $entity;
    }

    public function save(DataProviderCredentials $entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
}
