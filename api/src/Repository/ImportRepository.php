<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Import;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ImportRepository
{
    /**
     * @var EntityRepository<Import>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(Import::class);
    }

    public function findById(int $id): ?Import
    {
        return $this->repository->find($id);
    }

    /**
     * @return array<Import>
     */
    public function findAllByUserId(int $userId): array
    {
        return $this->repository->findBy(['userId' => $userId], ['id' => 'DESC']);
    }

    public function save(Import $import): void
    {
        $this->em->persist($import);
        $this->em->flush();
    }
}
