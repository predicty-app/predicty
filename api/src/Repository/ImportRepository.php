<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Campaign;
use App\Entity\FileImport;
use App\Entity\Import;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ImportRepository
{
    /**
     * @var EntityRepository<Campaign>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(Campaign::class);
    }

    public function findById(int $id): ?Import
    {
        return $this->repository->find($id);
    }

    public function findFileImportById(int $id): FileImport
    {
        return $this->em->getRepository(FileImport::class)->find($id);
    }

    public function save(Import $import): void
    {
        $this->em->persist($import);
        $this->em->flush();
    }
}
