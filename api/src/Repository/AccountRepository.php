<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use RuntimeException;

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

    public function findById(int $accountId): ?Account
    {
        return $this->repository->find($accountId);
    }

    /**
     * @return array<Account>
     */
    public function findAllByIds(array $accountIds): array
    {
        return $this->repository->findBy(['id' => $accountIds]);
    }

    public function save(Account $account): void
    {
        $this->em->persist($account);
        $this->em->flush();
    }

    public function getById(int $accountId): Account
    {
        $account = $this->findById($accountId);

        if ($account === null) {
            throw new RuntimeException('Account not found');
        }

        return $account;
    }
}
