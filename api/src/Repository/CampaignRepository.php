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

    public function findByUserIdAndName(int $userId, string $name): ?Campaign
    {
        return $this->repository->findOneBy(['userId' => $userId, 'name' => $name]);
    }

    public function save(Campaign $campaign)
    {
        $this->em->persist($campaign);
        $this->em->flush();
    }
}
