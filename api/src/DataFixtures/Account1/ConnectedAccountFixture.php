<?php

declare(strict_types=1);

namespace App\DataFixtures\Account1;

use App\DataFixtures\AccountFixture;
use App\DataFixtures\UserFixture;
use App\Entity\Account;
use App\Entity\ConnectedAccount;
use App\Entity\DataProvider;
use App\Entity\DoctrineUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Ulid;

class ConnectedAccountFixture extends Fixture implements DependentFixtureInterface
{
    public const CONNECTED_ACCOUNT_1 = '01H1S4490FZZSVPZ5ZKEE6Q3R4';

    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = $this->getReference(UserFixture::JOHN, DoctrineUser::class);
        $account = $this->getReference(AccountFixture::ACCOUNT_1, Account::class);

        $entity = new ConnectedAccount(Ulid::fromString(self::CONNECTED_ACCOUNT_1), $account->getId(), $user->getId(), DataProvider::GOOGLE_ANALYTICS, [], false);
        $manager->persist($entity);
        $this->addReference(self::CONNECTED_ACCOUNT_1, $entity);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
            AccountFixture::class,
        ];
    }
}
