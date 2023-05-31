<?php

declare(strict_types=1);

namespace App\DataFixtures\Account1;

use App\DataFixtures\AccountFixture;
use App\DataFixtures\UserFixture;
use App\Entity\Account;
use App\Entity\ApiImport;
use App\Entity\DataProvider;
use App\Entity\DoctrineUser;
use App\Entity\FileImport;
use App\Entity\FileImportType;
use App\Entity\ImportResult;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Ulid;

/**
 * All imports are created for the same account and user.
 */
class ImportFixture extends Fixture implements DependentFixtureInterface
{
    public const IMPORT_1 = '01H1S47C1TNVJ4HT2HADPCXN7J';
    public const IMPORT_2 = '01H1S47HH28B0STAS89S7YRZN1';
    public const IMPORT_3 = '01H1S47Q6M2SREPBQ6EKSS2EFM';
    public const IMPORT_4 = '01H1S47W6PKZ4SB6N0MVX9407X';
    public const IMPORT_5 = '01H1S481KHGQ12CK519CA0WBAR';

    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = $this->getReference(UserFixture::JOHN, DoctrineUser::class);
        $account = $this->getReference(AccountFixture::ACCOUNT_1, Account::class);
        assert($user instanceof DoctrineUser);

        $import1 = new FileImport(Ulid::fromString(self::IMPORT_1), $user->getId(), $account->getId(), 'dummy-import-1.csv', FileImportType::FACEBOOK_CSV);
        $this->addReference(self::IMPORT_1, $import1);

        $import2 = new FileImport(Ulid::fromString(self::IMPORT_2), $user->getId(), $account->getId(), 'dummy-import-2.csv', FileImportType::GOOGLE_ANALYTICS_REVENUE);
        $import2->start();
        $this->addReference(self::IMPORT_2, $import2);

        $import3 = new FileImport(Ulid::fromString(self::IMPORT_3), $user->getId(), $account->getId(), 'dummy-import-3.csv', FileImportType::GOOGLE_ADS_CSV);
        $import3->start();
        $import3->fail('Import failed');
        $this->addReference(self::IMPORT_3, $import3);

        $import4 = new ApiImport(Ulid::fromString(self::IMPORT_4), $user->getId(), $account->getId(), DataProvider::GOOGLE_ADS);
        $import4->start();
        $this->addReference(self::IMPORT_4, $import4);

        $import5 = new FileImport(Ulid::fromString(self::IMPORT_5), $user->getId(), $account->getId(), 'dummy-import-1.csv', FileImportType::FACEBOOK_CSV);
        $import5->start();
        $import5->complete(new ImportResult(2, 4, 10, 50, 50));
        $this->addReference(self::IMPORT_5, $import5);

        $manager->persist($import1);
        $manager->persist($import2);
        $manager->persist($import3);
        $manager->persist($import4);
        $manager->persist($import5);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
        ];
    }
}
