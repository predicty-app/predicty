<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Param;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ParamRepository
{
    /**
     * @var EntityRepository<Param>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(Param::class);
    }

    public function findByName(string $name): ?Param
    {
        return $this->repository->findOneBy(['name' => $name]);
    }

    public function findByNameOrCreate(string $name): Param
    {
        $param = $this->repository->findOneBy(['name' => $name]);

        if ($param === null) {
            $param = new Param($name);
        }

        return $param;
    }

    public function saveAndFlush(Param $entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
}
