<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AdCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use RuntimeException;

class AdCollectionRepository
{
    /**
     * @var EntityRepository<AdCollection>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(AdCollection::class);
    }

    public function getById(int $adCollectionId): AdCollection
    {
        $adCollection = $this->repository->find($adCollectionId);
        if ($adCollection === null) {
            throw new RuntimeException('AdCollection was not found');
        }

        return $adCollection;
    }

    /**
     * @return array<AdCollection>
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @return array<AdCollection>
     */
    public function findAllByUserId(int $userId): array
    {
        return $this->repository->findBy(['userId' => $userId]);
    }

    public function findById(int $adCollectionId): ?AdCollection
    {
        return $this->repository->find($adCollectionId);
    }

    public function save(AdCollection $adCollection): void
    {
        $this->em->persist($adCollection);
        $this->em->flush();
    }
}
