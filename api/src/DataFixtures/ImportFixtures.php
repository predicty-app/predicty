<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ApiImport;
use App\Entity\DataProvider;
use App\Entity\FileImport;
use App\Entity\FileImportType;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ImportFixtures extends Fixture implements DependentFixtureInterface
{
    public const IMPORT_1 = 'IMPORT1';
    public const IMPORT_2 = 'IMPORT2';
    public const IMPORT_3 = 'IMPORT3';
    public const IMPORT_4 = 'IMPORT4';

    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = $this->getReference(UserFixtures::JOHN, User::class);
        assert($user instanceof User);

        $import1 = new FileImport($user->getId(), 'dummy-import-1.csv', FileImportType::FACEBOOK_CSV);
        $this->addReference(self::IMPORT_1, $import1);

        $import2 = new FileImport($user->getId(), 'dummy-import-2.csv', FileImportType::GOOGLE_ANALYTICS_REVENUE);
        $import2->start();
        $this->addReference(self::IMPORT_2, $import2);

        $import3 = new FileImport($user->getId(), 'dummy-import-3.csv', FileImportType::GOOGLE_ADS_CSV);
        $import3->start();
        $import3->fail('Import failed');
        $this->addReference(self::IMPORT_3, $import3);

        $import4 = new ApiImport($user->getId(), DataProvider::GOOGLE_ADS);
        $import4->start();
        $this->addReference(self::IMPORT_4, $import4);

        $manager->persist($import1);
        $manager->persist($import2);
        $manager->persist($import3);
        $manager->persist($import4);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
