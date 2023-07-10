<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AccountInvitation;
use App\Service\Clock\Clock;
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

    public function findById(Ulid $invitationId): ?AccountInvitation
    {
        return $this->repository->find($invitationId);
    }

    public function finByIdAndSkipExpired(Ulid $invitationId): ?AccountInvitation
    {
        $qb = $this->repository->createQueryBuilder('ai');
        $qb->where('ai.id = :id')
            ->andWhere('ai.validTo > :now')
            ->setParameter('id', $invitationId)
            ->setParameter('now', Clock::now());

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getById(Ulid $invitationId): AccountInvitation
    {
        return $this->findById($invitationId) ?? throw new RuntimeException('Account invitation not found');
    }

    public function save(AccountInvitation $accountInvitation): void
    {
        $this->em->persist($accountInvitation);
        $this->em->flush();
    }

    public function remove(AccountInvitation $invitation): void
    {
        $this->em->remove($invitation);
        $this->em->flush();
    }
}
