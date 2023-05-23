<?php

declare(strict_types=1);

namespace App\Service\Google\Ads;

use Symfony\Component\Uid\Ulid;

interface GoogleAdsCredentialsProvider
{
    public function getDeveloperToken(): string;

    public function getCredentials(Ulid $connectedAccountId): GoogleAdsCredentials;
}
