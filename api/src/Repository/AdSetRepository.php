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

    public function findByCampaignIdAndName(int $campaignId, mixed $name): ?AdSet
    {
        return $this->repository->findOneBy(['campaignId' => $campaignId, 'name' => $name]);
    }

    public function save(AdSet $adSet): void
    {
        $this->em->persist($adSet);
        $this->em->flush();
    }
}
