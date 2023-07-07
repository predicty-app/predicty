<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AccountInvitation;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use RuntimeException;
use Symfony\Component\Uid\Ulid;

class AccountInvitationRepository
{
    /**
     * @var EntityRepository<AccountInvitation>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(AccountInvitation::class);
    }

    public function findById(Ulid $accountId): ?AccountInvitation
    {
        return $this->repository->find($accountId);
    }

    public function getById(Ulid $accountId): AccountInvitation
    {
        return $this->findById($accountId) ?? throw new RuntimeException('Account invitation not found');
    }

    public function save(AccountInvitation $accountInvitation): void
    {
        $this->em->persist($accountInvitation);
        $this->em->flush();
    }
}
