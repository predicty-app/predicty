<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AdCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

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

    public function save(AdCollection $adCollection): void
    {
        $this->em->persist($adCollection);
        $this->em->flush();
    }

    /**
     * @return array<AdCollection>
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }
}