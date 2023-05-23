<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ConnectedAccount;
use App\Entity\DataProvider;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use RuntimeException;
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

    public function findById(Ulid $id): ?ConnectedAccount
    {
        return $this->repository->find($id);
    }

    public function getById(Ulid $accountId): ConnectedAccount
    {
        return $this->findById($accountId) ?? throw new RuntimeException('Connected account not found');
    }

    /**
     * @template T of ConnectedAccount
     *
     * @param class-string<T> $connectedAccountType
     *
     * @return T
     */
    public function findByAccountId(Ulid $accountId, string $connectedAccountType): ?ConnectedAccount
    {
        assert(is_subclass_of($connectedAccountType, ConnectedAccount::class));

        return $this->em->getRepository($connectedAccountType)->findOneBy(['accountId' => $accountId]);
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
