<?php

declare(strict_types=1);

namespace App\Service\Google\Ads;

use Google\Ads\GoogleAds\Lib\V12\GoogleAdsClient as WrappedGoogleAdsClient;
use Google\Ads\GoogleAds\V12\Services\GoogleAdsRow;

class GoogleAdsClient
{
    public function __construct(private string $customerId, private WrappedGoogleAdsClient $google)
    {
    }

    public function getAllCampaigns(): void
    {
        $googleAdsServiceClient = $this->google->getGoogleAdsServiceClient();
        $query = 'SELECT campaign.id, campaign.name FROM campaign ORDER BY campaign.id';
        $query = 'SELECT ad_group.id, ad_group.name, campaign.id, ad_group.campaign, metrics.all_conversions_by_conversion_date, metrics.all_conversions_value_by_conversion_date, campaign.start_date, campaign.end_date FROM ad_group WHERE segments.date DURING LAST_30_DAYS';
        $query = 'SELECT ad_group_ad.ad.id, ad_group.id, campaign.id, customer.id, ad_group_ad.ad.name, ad_group.name, campaign.name, campaign.start_date, campaign.end_date FROM ad_group_ad';
        $query = 'SELECT ad_group_ad.ad.id, ad_group.id, campaign.id, customer.id, ad_group_ad.ad.name, ad_group.name, campaign.name, campaign.start_date, campaign.end_date, metrics.all_conversions_value_by_conversion_date, metrics.all_conversions_by_conversion_date, metrics.conversions_value_by_conversion_date, ad_group_ad.ad.text_ad.headline, ad_group_ad.ad.responsive_search_ad.headlines, segments.date FROM ad_group_ad';
        //        $query = 'SELECT ad_group_ad.ad.id, ad_group.id, campaign.id, customer.id, ad_group_ad.ad.name, ad_group.name, campaign.name, campaign.start_date, campaign.end_date, metrics.all_conversions_value_by_conversion_date, metrics.all_conversions_by_conversion_date, metrics.conversions_value_by_conversion_date, ad_group_ad.ad.text_ad.headline, ad_group_ad.ad.responsive_search_ad.headlines, segments.date FROM ad_group_ad WHERE segments.date = \'2023-05-23\'';

        $response = $googleAdsServiceClient->search($this->customerId, $query, ['pageSize' => 1000]);

        foreach ($response->iterateAllElements() as $row) {
            /** @var GoogleAdsRow $row */
            dump(
                $row->getSegments()?->getDate(),
                $row->getAdGroupAd()?->getAd()?->getId(),
                $row->getAdGroupAd()?->getAd()?->getResponsiveSearchAd()?->getHeadlines()[0]?->getText(),
                $row->getAdGroup()?->getId(),
                $row->getAdGroup()?->getName(),
                $row->getCampaign()?->getId(),
                $row->getCampaign()?->getName(),
                $row->getCampaign()?->getStartDate(),
                $row->getCampaign()?->getEndDate()
            );
        }
    }
}
