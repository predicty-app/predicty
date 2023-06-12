<?php

declare(strict_types=1);

namespace App\Service\Google\Analytics;

/**
 * Represents the GA credentials used to access the Google Analytics API.
 */
interface GoogleAnalyticsCredentials
{
    public function getRefreshToken(): string;

    public function getGA4Id(): string;
}
