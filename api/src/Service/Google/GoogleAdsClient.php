<?php

declare(strict_types=1);

namespace App\Service\Google;

use Google\Ads\GoogleAds\Lib\V10\GoogleAdsClient as WrappedGoogleAdsClient;

class GoogleAdsClient
{
    public function __construct(private WrappedGoogleAdsClient $google)
    {
    }
}
