<?php

declare(strict_types=1);

namespace App\Service\Google\Ads;

use App\Service\DataImport\DataImportApi;
use Symfony\Component\Uid\Ulid;

class GoogleAdsUpdater
{
    public function __construct(
        // @phpstan-ignore-next-line
        private DataImportApi $dataImportApi,
        private GoogleAdsClientBuilder $googleAdsClientFactory
    ) {
    }

    public function update(Ulid $userId, Ulid $accountId, Ulid $connectedAccountId): void
    {
        $googleAdsClient = $this->googleAdsClientFactory->build($connectedAccountId);
        $googleAdsClient->getAllCampaigns();
    }
}
