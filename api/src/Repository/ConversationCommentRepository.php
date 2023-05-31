<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ConversationComment;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use RuntimeException;
use Symfony\Component\Uid\Ulid;

class ConversationCommentRepository
{
    /**
     * @var EntityRepository<ConversationComment>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(ConversationComment::class);
    }

    public function getById(Ulid $commentId): ConversationComment
    {
        $comment = $this->repository->find($commentId);
        if ($comment === null) {
            throw new RuntimeException('Comment not found.');
        }

        return $comment;
    }

    public function findById(Ulid $id): ?ConversationComment
    {
        return $this->repository->find($id);
    }

    /**
     * @return array<ConversationComment>
     */
    public function findByConversationId(Ulid $conversationId): array
    {
        return $this->repository->findBy(['conversationId' => $conversationId], ['id' => 'ASC']);
    }

    public function save(ConversationComment $conversationComment): void
    {
        $this->em->persist($conversationComment);
        $this->em->flush();
    }

    public function removeById(Ulid $commentId): void
    {
        $comment = $this->repository->find($commentId);
        if ($comment === null) {
            return;
        }

        $this->remove($comment);
    }

    public function removeByConversationId(Ulid $conversationId): void
    {
        $comments = $this->repository->findBy(['conversationId' => $conversationId]);
        foreach ($comments as $comment) {
            $this->em->remove($comment);
        }
        $this->em->flush();
    }

    public function remove(ConversationComment $comment): void
    {
        $this->em->remove($comment);
        $this->em->flush();
    }
}
