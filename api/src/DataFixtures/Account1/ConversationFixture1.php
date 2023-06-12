<?php

declare(strict_types=1);

namespace App\DataFixtures\Account1;

use App\DataFixtures\AccountFixture;
use App\DataFixtures\UserFixture;
use App\Entity\Account;
use App\Entity\Color;
use App\Entity\Conversation;
use App\Entity\DoctrineUser;
use App\Service\Util\DateHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Ulid;

class ConversationFixture1 extends Fixture implements DependentFixtureInterface
{
    public const CONVERSATION_1 = '01H1PR6QCVET3PEDGV8SAAF2KM';

    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = $this->getReference(UserFixture::JOHN, DoctrineUser::class);
        $account = $this->getReference(AccountFixture::ACCOUNT_1, Account::class);

        $conversation = new Conversation(
            id: Ulid::fromString(self::CONVERSATION_1),
            userId: $user->getId(),
            accountId: $account->getId(),
            date: DateHelper::fromString('2021-01-05'),
            color: Color::fromString('#0099ff')
        );

        $this->addReference(self::CONVERSATION_1, $conversation);
        $manager->persist($conversation);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
        ];
    }
}
