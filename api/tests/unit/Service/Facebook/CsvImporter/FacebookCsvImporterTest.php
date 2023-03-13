<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Facebook\CsvImporter;

use App\Entity\Ad;
use App\Entity\Campaign;
use App\Factory\AdFactory;
use App\Factory\AdSetFactory;
use App\Factory\AdStatsFactory;
use App\Factory\CampaignFactory;
use App\Service\Facebook\CsvImporter\FacebookCsvImporter;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Service\Facebook\CsvImporter\FacebookCsvImporter
 */
class FacebookCsvImporterTest extends TestCase
{
    private CampaignFactory&MockObject $campaignFactory;
    private AdSetFactory&MockObject $adSetFactory;
    private AdFactory&MockObject $adFactory;
    private MockObject&AdStatsFactory $adStatsFactory;
    private FacebookCsvImporter $importer;
    private MockObject&EntityManagerInterface $em;

    protected function setUp(): void
    {
        parent::setUp();

        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->em->method('transactional')->willReturnCallback(function ($callback): void {
            $callback();
        });

        $this->campaignFactory = $this->createMock(CampaignFactory::class);
        $this->adSetFactory = $this->createMock(AdSetFactory::class);
        $this->adFactory = $this->createMock(AdFactory::class);
        $this->adStatsFactory = $this->createMock(AdStatsFactory::class);

        $this->importer = new FacebookCsvImporter(
            entityManager: $this->em,
            campaignFactory: $this->campaignFactory,
            adSetFactory: $this->adSetFactory,
            adFactory: $this->adFactory,
            adStatsFactory: $this->adStatsFactory
        );
    }

    public function test_import_creates_campaign(): void
    {
        $this->campaignFactory->expects($this->exactly(2))->method('make')
            ->withConsecutive(
                [1, 'Campaign 1', '23852952857920492'],
                [1, 'Campaign 2', '23852953378710492'],
            );

        $fileStream = fopen(__DIR__.'/data1.csv', 'r');
        $this->importer->import(1, $fileStream);
    }

    public function test_import_creates_ad_set(): void
    {
        $this->adSetFactory->expects($this->exactly(2))
            ->method('make')
            ->withConsecutive(
                [$this->isInstanceOf(Campaign::class), '', '23852952857910492'],
                [$this->isInstanceOf(Campaign::class), '', '23853080679590492']
            );

        $fileStream = fopen(__DIR__.'/data1.csv', 'r');
        $this->importer->import(1, $fileStream);
    }

    public function test_import_creates_ad_stats(): void
    {
        $this->adStatsFactory->expects($this->exactly(2))
            ->method('make')
            ->withConsecutive(
                [
                    $this->isInstanceOf(Ad::class),
                    $this->isInstanceOf(DateTimeImmutable::class),
                    5,
                    $this->anything(),
                    $this->anything(),
                ],
                [
                    $this->isInstanceOf(Ad::class),
                    $this->isInstanceOf(DateTimeImmutable::class),
                    3,
                    $this->anything(),
                    $this->anything(),
                ]
            );

        $fileStream = fopen(__DIR__.'/data1.csv', 'r');
        $this->importer->import(1, $fileStream);
    }

    public function test_import_clears_entity_manager_after_flush(): void
    {
        $this->em->expects($this->once())->method('clear');

        $fileStream = fopen(__DIR__.'/data1.csv', 'r');
        $this->importer->import(1, $fileStream);
    }
}
