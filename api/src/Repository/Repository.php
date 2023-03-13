<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @template T
 */
abstract class Repository
{
    private EntityRepository $repository;

    /**
     * @param class-string<T> $entityName
     */
    public function __construct(protected EntityManagerInterface $em, string $entityName)
    {
        $this->repository = $em->getRepository($entityName);
    }

    /**
     * @return T of EntityRepository
     */
    protected function getRepository(): EntityRepository
    {
        return $this->repository;
    }
//
//    public function save(object $entity): void
//    {
//        $this->em->persist($entity);
//        $this->em->flush();
//    }
}
