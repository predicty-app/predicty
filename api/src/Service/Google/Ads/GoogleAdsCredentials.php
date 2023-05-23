<?php

declare(strict_types=1);

namespace App\Service\Google\Ads;

interface GoogleAdsCredentials
{
    public function getRefreshToken(): string;

    public function getCustomerId(): string;
}
