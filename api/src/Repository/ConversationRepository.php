<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Conversation;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use RuntimeException;

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

    public function getById(int $conversationId): Conversation
    {
        $conversation = $this->repository->find($conversationId);
        if ($conversation === null) {
            throw new RuntimeException('Conversation not found');
        }

        return $conversation;
    }

    public function findByUserIdAndDate(int $userId, DateTimeImmutable $date): ?Conversation
    {
        return $this->repository->findOneBy(['userId' => $userId, 'date' => $date]);
    }

    /**
     * @return array<Conversation>
     */
    public function findAllByUserId(int $userId): array
    {
        return $this->repository->findBy(['userId' => $userId], ['id' => 'DESC']);
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
