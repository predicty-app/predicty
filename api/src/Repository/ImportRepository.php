<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Ad;
use App\Entity\AdSet;
use App\Entity\AdStats;
use App\Entity\Campaign;
use App\Entity\Import;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

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

    public function getById(Ulid $importId): Import
    {
        $user = $this->repository->find($importId);
        assert($user instanceof Import, 'Import not found');

        return $user;
    }

    public function findById(Ulid $id): ?Import
    {
        return $this->repository->find($id);
    }

    /**
     * @return array<Import>
     */
    public function findAllByUserId(Ulid $userId): array
    {
        return $this->repository->findBy(['userId' => $userId], ['id' => 'DESC']);
    }

    public function removeAllAssociatedEntities(Ulid $importId): void
    {
        $qb = $this->em->createQueryBuilder();

        $entityClasses = [
            AdStats::class,
            Ad::class,
            AdSet::class,
            Campaign::class,
        ];

        foreach ($entityClasses as $entityClass) {
            $qb->delete($entityClass, 'e')
                ->where('e.importId = :importId')
                ->setParameter('importId', $importId, UlidType::NAME)
                ->getQuery()
                ->execute();
        }
    }

    public function save(Import $import): void
    {
        $this->em->persist($import);
        $this->em->flush();
    }
}
