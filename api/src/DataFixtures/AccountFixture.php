<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Account;
use App\Entity\DoctrineUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Ulid;

class AccountFixture extends Fixture implements DependentFixtureInterface
{
    public const ACCOUNT_1 = '01H1PQRVGZ665993NY6MCZ2J6X';
    public const ACCOUNT_2 = '01H1PQS62MQGN0GG41T16KP77Y';

    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        /** @var DoctrineUser $user1 */
        $user1 = $this->getReference(UserFixture::JOHN, DoctrineUser::class);

        /** @var DoctrineUser $user2 */
        $user2 = $this->getReference(UserFixture::JANE, DoctrineUser::class);

        /** @var DoctrineUser $user3 */
        $user3 = $this->getReference(UserFixture::JACK, DoctrineUser::class);

        $account1 = new Account(Ulid::fromString(self::ACCOUNT_1), $user1->getId(), 'Account 1');
        $account2 = new Account(Ulid::fromString(self::ACCOUNT_2), $user1->getId(), 'Account 2');

        $this->addReference(self::ACCOUNT_1, $account1);
        $this->addReference(self::ACCOUNT_2, $account2);

        $manager->persist($account1);
        $manager->persist($account2);
        $manager->flush();

        $user1->setAsAccountOwner($account1->getId());
        $user1->setAsAccountOwner($account2->getId());
        $user2->setAsAccountMember($account1->getId());
        $user3->setAsAccountMember($account1->getId());

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
        ];
    }
}
