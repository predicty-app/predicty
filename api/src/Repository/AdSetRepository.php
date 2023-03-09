<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AdSet;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class AdSetRepository
{
    /**
     * @var EntityRepository<AdSet>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(AdSet::class);
    }

    /**
     * @return array<AdSet>
     */
    public function findAllByCampaignId(int $campaignId): array
    {
        return $this->repository->findBy(['campaignId' => $campaignId]);
    }

    public function findByCampaignIdAndExternalId(int $userId, int $campaignId, mixed $externalId): ?AdSet
    {
        return $this->repository->findOneBy([
            'userId' => $userId,
            'campaignId' => $campaignId,
            'externalId' => $externalId,
        ]);
    }

    public function save(AdSet $adSet): void
    {
        $this->em->persist($adSet);
        $this->em->flush();
    }
}
