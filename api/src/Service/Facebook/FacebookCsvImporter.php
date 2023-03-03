<?php

declare(strict_types=1);

namespace App\Service\Facebook;

use App\Entity\Ad;
use App\Entity\AdSet;
use App\Entity\Campaign;
use App\Repository\AdRepository;
use App\Repository\AdSetRepository;
use App\Repository\CampaignRepository;
use Brick\Money\Currency;
use Brick\Money\Money;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use League\Csv\Statement;
use Psr\Clock\ClockInterface;

class FacebookCsvImporter
{
    private const HEADER_CAMPAIGN_NAME = 'Campaign name';
    private const HEADER_AD_SET_NAME = 'Ad Set Name';

    private const HEADER_DAY = 'Day';
    private const HEADER_REACH = 'Reach';
    private const HEADER_RESULTS = 'Results';
    private const HEADER_IMPRESSIONS = 'Impressions';
    private const HEADER_COST_PER_RESULT = 'Cost per result';
    private const HEADER_AMOUNT_SPENT = 'Amount spent (PLN)';
    const HEADER_COST = '';

    /**
     * @var array<Campaign>
     */
    private array $campaigns = [];

    /**
     * @var array<AdSet>
     */
    private array $adSets = [];

    private Campaign $currentCampaign;

    public function __construct(
        private ClockInterface $clock,
        private CampaignRepository $campaignRepository,
        private AdSetRepository $adSetRepository,
        private AdRepository $adRepository,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function import(int $userId, string $filename): void
    {
        $csv = Reader::createFromPath($filename);
        $csv->setHeaderOffset(0);

        $stmt = Statement::create();
        $records = $stmt->process($csv);

        $batch = [];

        foreach ($records as $record) {
            $batch[] = function() use ($userId, $record){
                $campaign = $this->createCampaign($userId, $record);
                $adSet = $this->createAdSet($campaign, $record);
                $this->createAd($adSet, $record);
            };
        }

        $this->entityManager->wrapInTransaction(function () use ($batch) {
            foreach ($batch as $record) {
                $record();
            }
        });
    }

    private function createCampaign(int $userId, array $row): Campaign
    {
        $name = $row[self::HEADER_CAMPAIGN_NAME];
        $campaign = $this->campaigns[$name] ?? null;

        if($campaign === null) {
            $campaign = $this->campaignRepository->findByUserIdAndName($userId, $name);
        }

        if($campaign === null) {
            $campaign = new Campaign(
                userId: $userId,
                name: $name,
                importedAt: $this->clock->now()
            );
        }

        $this->campaignRepository->save($campaign);

        $this->campaigns[$name] = $campaign;
        return $campaign;
    }

    private function createAdSet(Campaign $campaign, mixed $row): AdSet
    {
        $name = $row['Ad Set Name'];
        $adSet = $this->adSets[$name] ?? null;

        if($adSet === null) {
            $adSet = $this->adSetRepository->findByCampaignIdAndName($campaign->getId(), $name);
        }

        if($adSet === null) {
            $adSet = new AdSet(
                userId: $campaign->getUserId(),
                campaignId: $campaign->getId(),
                name: $name,
                importedAt: $this->clock->now()
            );
        }

        $this->adSets[$name] = $adSet;
        return $adSet;
    }

    private function createAd(AdSet $adSet, mixed $record): Ad
    {
        $currency = Currency::of('PLN');

        $ad = new Ad(
            userId: $adSet->getUserId(),
            adSetId: $adSet->getId(),
            campaignId: $adSet->getCampaignId(),
            reach: (int) $record[self::HEADER_REACH],
            results: (int) $record[self::HEADER_RESULTS],
            impressions: (int) $record[self::HEADER_IMPRESSIONS],
            costPerResult: $this->normalizeAmount($record[self::HEADER_COST_PER_RESULT], $currency),
            cost: $this->normalizeAmount($record[self::HEADER_AMOUNT_SPENT], $currency),
            currency: $currency->getCurrencyCode(),
            date: $this->date($record[self::HEADER_DAY]),
            importedAt: $this->clock->now(),
        );

        $this->adRepository->save($ad);
        return $ad;
    }

    private function normalizeAmount(string|float $amount, Currency $currency): int
    {
        return Money::of($amount, $currency)->getAmount()->toInt();
    }

    private function date(string $date): \DateTimeInterface
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d', $date);

        if($date === false) {
            throw new \RuntimeException(sprintf('Invalid date format given: "%s" (expecting Y-m-d)', $date));
        }

        return $date;
    }


}
