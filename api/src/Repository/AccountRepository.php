<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use RuntimeException;
use Symfony\Component\Uid\Ulid;

class AccountRepository
{
    /**
     * @var EntityRepository<Account>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(Account::class);
    }

    public function findById(Ulid $accountId): ?Account
    {
        return $this->repository->find($accountId);
    }

    public function getById(Ulid $accountId): Account
    {
        return $this->findById($accountId) ?? throw new RuntimeException('Account not found');
    }

    /**
     * @return array<Account>
     */
    public function findAllByIds(array $accountIds): array
    {
        $accountIds = array_map(fn (Ulid $accountId) => $accountId->toRfc4122(), $accountIds);

        return $this->repository->findBy(['id' => $accountIds]);
    }

    public function save(Account $account): void
    {
        $this->em->persist($account);
        $this->em->flush();
    }
}
