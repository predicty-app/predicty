<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Campaign;
use App\Entity\User;
use App\Service\Util\DateHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CampaignFixtures extends Fixture implements DependentFixtureInterface
{
    public const CAMPAIGN_1 = 'CAMPAIGN1';
    public const CAMPAIGN_2 = 'CAMPAIGN2';
    public const CAMPAIGN_3 = 'CAMPAIGN3';

    public function load(ObjectManager $manager): void
    {
        /** @var User $user */
        $user = $this->getReference(UserFixtures::JOHN);

        $rows = [
            [self::CAMPAIGN_1, 'external-id-1', $user->getId(), 'Campaign 1', '2023-01-02 00:00:00', '2023-01-18 23:59:59'],
            [self::CAMPAIGN_2, 'external-id-2', $user->getId(), 'Campaign 2'],
            [self::CAMPAIGN_3, 'external-id-3', $user->getId(), 'Campaign 3'],
        ];

        foreach ($rows as $row) {
            $entity = new Campaign(
                externalId: $row[1],
                userId: $row[2],
                name: $row[3],
                startedAt: isset($row[4]) ? DateHelper::fromString($row[4], 'Y-m-d H:i:s') : null,
                endedAt: isset($row[5]) ? DateHelper::fromString($row[5], 'Y-m-d H:i:s') : null,
            );

            $manager->persist($entity);
            $this->addReference($row[0], $entity);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
