<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Conversation;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use RuntimeException;
use Symfony\Component\Uid\Ulid;

class ConversationRepository
{
    /**
     * @var EntityRepository<Conversation>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(Conversation::class);
    }

    public function getById(Ulid $conversationId): Conversation
    {
        $conversation = $this->repository->find($conversationId);
        if ($conversation === null) {
            throw new RuntimeException('Conversation not found');
        }

        return $conversation;
    }

    public function findByAccountIdAndDate(Ulid $accountId, DateTimeImmutable $date): ?Conversation
    {
        return $this->repository->findOneBy(['accountId' => $accountId, 'date' => $date]);
    }

    /**
     * @return array<Conversation>
     */
    public function findAllByAccountId(Ulid $accountId): array
    {
        return $this->repository->findBy(['accountId' => $accountId], ['id' => 'DESC']);
    }

    public function save(Conversation $conversation): void
    {
        $this->em->persist($conversation);
        $this->em->flush();
    }

    public function remove(Conversation $conversation): void
    {
        $this->em->remove($conversation);
        $this->em->flush();
    }
}
