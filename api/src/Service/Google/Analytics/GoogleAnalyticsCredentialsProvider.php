<?php

declare(strict_types=1);

namespace App\Service\Google\Analytics;

use Symfony\Component\Uid\Ulid;

/**
 * Provides access to stored Google Analytics credentials.
 */
interface GoogleAnalyticsCredentialsProvider
{
    public function getCredentials(Ulid $connectedAccountId): GoogleAnalyticsCredentials;
}
