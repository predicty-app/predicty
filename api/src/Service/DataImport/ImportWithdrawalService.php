<?php

declare(strict_types=1);

namespace App\Service\DataImport;

use App\Repository\ImportRepository;
use Doctrine\ORM\EntityManagerInterface;

class ImportWithdrawalService
{
    public function __construct(
        private ImportRepository $importRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function withdraw(int $importId): void
    {
        $import = $this->importRepository->getById($importId);
        $this->entityManager->transactional(function () use ($import): void {
            $import->withdraw();
            $this->importRepository->removeAllAssociatedEntities($import->getId());
            $this->importRepository->save($import);
        });
    }
}
