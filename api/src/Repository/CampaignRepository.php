<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Campaign;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Uid\Ulid;

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

    public function findById(Ulid $id): ?Campaign
    {
        return $this->repository->find($id);
    }

    /**
     * @return array<Campaign>
     */
    public function findAllByAccountId(Ulid $accountId): array
    {
        return $this->repository->findBy(['accountId' => $accountId], ['startedAt' => 'ASC']);
    }

    public function findByUserIdAndExternalId(Ulid $userId, string $externalId): ?Campaign
    {
        return $this->repository->findOneBy(['userId' => $userId, 'externalId' => $externalId]);
    }

    /**
     * @return array<Campaign>
     */
    public function findAll(int $limit = 10): array
    {
        return $this->repository->findBy([], null, $limit);
    }

    public function save(Campaign $campaign): void
    {
        $this->em->persist($campaign);
        $this->em->flush();
    }
}
