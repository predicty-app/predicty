<?php

declare(strict_types=1);

namespace App\Service\Facebook;

use FacebookAds\Object\AdsInsights;

class FacebookInsightsConversionCounter
{
    public static function count(AdsInsights $insights): int
    {
        $conversions = 0;
        $data = $insights->getData();

        if (isset($data['conversions']) && is_array($data['conversions'])) {
            foreach ($data['conversions'] as $conversion) {
                if (self::isActionAllowed($conversion['action_type'])) {
                    $conversions += $conversion['value'];
                }
            }
        }

        return $conversions;
    }

    private static function isActionAllowed(string $action): bool
    {
        // do not sum up conversions from website, app and offline - only totals
        return preg_match('#^.*_(website|app|offline)$#', $action) === 0;
    }
}
