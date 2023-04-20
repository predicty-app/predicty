<?php

declare(strict_types=1);

namespace App\Test;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

trait EntityManagerTrait
{
    use KernelBrowserTrait;

    /**
     * @template T of object
     *
     * @param class-string<T> $className
     *
     * @return EntityRepository<T>
     */
    public function getRepository(string $className): EntityRepository
    {
        return $this->getEntityManager()->getRepository($className);
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return static::getClient()->getContainer()->get(EntityManagerInterface::class);
    }
}
