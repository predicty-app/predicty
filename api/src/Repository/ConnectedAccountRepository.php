<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ConnectedAccount;
use App\Entity\DataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Uid\Ulid;

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

    public function find(Ulid $accountId, DataProvider $type): ?ConnectedAccount
    {
        return $this->repository->findOneBy(['accountId' => $accountId, 'dataProvider' => $type]);
    }

    /**
     * @return ConnectedAccount[]
     */
    public function findAll(Ulid $userId, ?DataProvider $type = null): array
    {
        $args = ['userId' => $userId];

        if ($type !== null) {
            $args['dataProvider'] = $type;
        }

        return $this->repository->findBy($args);
    }

    /**
     * @return ConnectedAccount[]
     */
    public function findAllByAccountId(Ulid $accountId): array
    {
        return $this->repository->findBy(['accountId' => $accountId]);
    }

    public function save(ConnectedAccount $entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
}
