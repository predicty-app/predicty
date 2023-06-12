<?php

declare(strict_types=1);

namespace App\DataFixtures\Account1;

use App\Entity\Conversation;
use App\Entity\ConversationComment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Ulid;

class ConversationCommentFixture extends Fixture implements DependentFixtureInterface
{
    public const COMMENT_1 = '01H1S4560AWC457FYBGG22R47P';
    public const COMMENT_2 = '01H1S45BHAAC368KV8C4PMAN62';

    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $conversation = $this->getReference(ConversationFixture1::CONVERSATION_1, Conversation::class);
        $comment1 = new ConversationComment(
            id: Ulid::fromString(self::COMMENT_1),
            conversationId: $conversation->getId(),
            userId: $conversation->getUserId(),
            accountId: $conversation->getAccountId(),
            comment: 'This is a first comment in that conversation'
        );

        $comment2 = new ConversationComment(
            id: Ulid::fromString(self::COMMENT_2),
            conversationId: $conversation->getId(),
            userId: $conversation->getUserId(),
            accountId: $conversation->getAccountId(),
            comment: 'This is a second comment in that conversation'
        );

        $this->addReference(self::COMMENT_1, $comment1);
        $this->addReference(self::COMMENT_2, $comment2);

        $manager->persist($comment1);
        $manager->persist($comment2);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ConversationFixture1::class,
        ];
    }
}
