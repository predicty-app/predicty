<?php

declare(strict_types=1);

namespace App\Service\Security\ConnectedAccountCredentials;

use App\Repository\ConnectedAccountRepository;
use App\Service\Google\Analytics\GoogleAnalyticsCredentials;
use App\Service\Google\Analytics\GoogleAnalyticsCredentialsProvider as GoogleAnalyticsCredentialsProviderInterface;
use RuntimeException;
use Symfony\Component\Uid\Ulid;

class GoogleAnalyticsCredentialsProvider implements GoogleAnalyticsCredentialsProviderInterface
{
    public function __construct(private ConnectedAccountRepository $connectedAccountRepository)
    {
    }

    public function getCredentials(Ulid $connectedAccountId): GoogleAnalyticsCredentials
    {
        $credentials = $this->connectedAccountRepository->findById($connectedAccountId);

        if (!$credentials instanceof GoogleAnalyticsCredentials) {
            throw new RuntimeException('Credentials were not found.');
        }

        return $credentials;
    }
}
