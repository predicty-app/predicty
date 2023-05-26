<?php

declare(strict_types=1);

namespace App\DataFixtures\Account1;

use App\DataFixtures\AccountFixture;
use App\DataFixtures\UserFixture;
use App\Entity\Account;
use App\Entity\Campaign;
use App\Entity\DataProvider;
use App\Entity\DoctrineUser;
use App\Service\Util\DateHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Ulid;

/**
 * All campaigns belong to John, to hist first account.
 */
class CampaignFixture extends Fixture implements DependentFixtureInterface
{
    public const CAMPAIGN_1 = '01H1S428W9NS5E3MFFE72Q30FR';
    public const CAMPAIGN_2 = '01H1S42EZYJX02NG4EFDJ6WJA1';
    public const CAMPAIGN_3 = '01H1S42QAX52HB00K9AK3V9NC8';

    public function load(ObjectManager $manager): void
    {
        /** @var DoctrineUser $user */
        $user = $this->getReference(UserFixture::JOHN);
        /** @var Account $account */
        $account = $this->getReference(AccountFixture::ACCOUNT_1, Account::class);

        $data = [
            self::CAMPAIGN_1 => new Campaign(Ulid::fromString(self::CAMPAIGN_1), $user->getId(), $account->getId(), 'external-id-1', 'Campaign 1', DataProvider::FACEBOOK_ADS, DateHelper::fromString('2023-01-02 00:00:00', 'Y-m-d H:i:s'), DateHelper::fromString('2023-01-18 23:59:59', 'Y-m-d H:i:s')),
            self::CAMPAIGN_2 => new Campaign(Ulid::fromString(self::CAMPAIGN_2), $user->getId(), $account->getId(), 'external-id-2', 'Campaign 2', DataProvider::FACEBOOK_ADS),
            self::CAMPAIGN_3 => new Campaign(Ulid::fromString(self::CAMPAIGN_3), $user->getId(), $account->getId(), 'external-id-3', 'Campaign 3', DataProvider::GOOGLE_ADS),
        ];

        foreach ($data as $reference => $entity) {
            $manager->persist($entity);
            $this->addReference($reference, $entity);
        }

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
