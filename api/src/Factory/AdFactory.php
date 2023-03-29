<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Ad;
use App\Entity\AdSet;
use App\Entity\Campaign;
use App\Repository\AdRepository;

class AdFactory
{
    public function __construct(private AdRepository $adRepository)
    {
    }

    public function make(AdSet $adSet, string $name, string $externalId): Ad
    {
        $ad = $this->adRepository->findByUserIdAndExternalId($adSet->getUserId(), $externalId);

        if ($ad === null) {
            $ad = new Ad(
                userId: $adSet->getUserId(),
                externalId: $externalId,
                campaignId: $adSet->getCampaignId(),
                name: $name,
                adSetId: $adSet->getId(),
            );

            $this->adRepository->save($ad);
        }

        return $ad;
    }

    public function makeForCampaign(Campaign $campaign, string $name, string $externalId): Ad
    {
        $ad = $this->adRepository->findByUserIdAndExternalId($campaign->getUserId(), $externalId);

        if ($ad === null) {
            $ad = new Ad(
                userId: $campaign->getUserId(),
                externalId: $externalId,
                campaignId: $campaign->getId(),
                name: $name,
                adSetId: null
            );

            $this->adRepository->save($ad);
        }

        return $ad;
    }
}
