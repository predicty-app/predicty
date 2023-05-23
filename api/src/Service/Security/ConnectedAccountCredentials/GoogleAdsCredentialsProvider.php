<?php

declare(strict_types=1);

namespace App\Service\Security\ConnectedAccountCredentials;

use App\Repository\ConnectedAccountRepository;
use App\Service\Google\Ads\GoogleAdsCredentials;
use App\Service\Google\Ads\GoogleAdsCredentialsProvider as GoogleAdsCredentialsProviderInterface;
use RuntimeException;
use Symfony\Component\Uid\Ulid;

class GoogleAdsCredentialsProvider implements GoogleAdsCredentialsProviderInterface
{
    public function __construct(
        private string $developerToken,
        private ConnectedAccountRepository $connectedAccountRepository
    ) {
    }

    public function getCredentials(Ulid $connectedAccountId): GoogleAdsCredentials
    {
        $credentials = $this->connectedAccountRepository->findById($connectedAccountId);

        if (!$credentials instanceof GoogleAdsCredentials) {
            throw new RuntimeException('Credentials were not found.');
        }

        return $credentials;
    }

    public function getDeveloperToken(): string
    {
        return $this->developerToken;
    }
}
