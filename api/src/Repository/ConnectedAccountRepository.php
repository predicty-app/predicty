<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ConnectedAccount;
use App\Entity\DataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ConnectedAccountRepository
{
    /**
     * @var EntityRepository<ConnectedAccount>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $this->em->getRepository(ConnectedAccount::class);
    }

    public function findOrCreate(int $userId, DataProvider $type): ConnectedAccount
    {
        return $this->find($userId, $type) ?? new ConnectedAccount($userId, $type);
    }

    public function find(int $userId, DataProvider $type): ?ConnectedAccount
    {
        return $this->repository->findOneBy(['userId' => $userId, 'dataProvider' => $type]);
    }

    /**
     * @return ConnectedAccount[]
     */
    public function findAll(int $userId, ?DataProvider $type = null): array
    {
        $args = ['userId' => $userId];

        if ($type !== null) {
            $args['dataProvider'] = $type;
        }

        return $this->repository->findBy($args);
    }

    public function save(ConnectedAccount $entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
}
