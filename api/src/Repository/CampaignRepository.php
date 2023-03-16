<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Campaign;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CampaignRepository
{
    /**
     * @var EntityRepository<Campaign>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(Campaign::class);
    }

    public function findById(int $id): ?Campaign
    {
        return $this->repository->find($id);
    }

    public function findByUserIdAndName(int $userId, string $name): ?Campaign
    {
        return $this->repository->findOneBy(['userId' => $userId, 'name' => $name]);
    }

    public function findByUserIdAndExternalId(int $userId, string $externalId): ?Campaign
    {
        return $this->repository->findOneBy(['userId' => $userId, 'externalId' => $externalId]);
    }

    public function save(Campaign $campaign): void
    {
        $this->em->persist($campaign);
        $this->em->flush();
    }

    /**
     * @return array<Campaign>
     */
    public function findAll(int $limit = 10): array
    {
        return $this->repository->findBy([], null, $limit);
    }
}
