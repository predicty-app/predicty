<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Ad;
use App\Entity\AdSet;
use App\Repository\AdRepository;
use Psr\Clock\ClockInterface;

class AdFactory
{
    public function __construct(private AdRepository $adRepository, private ClockInterface $clock)
    {
    }

    public function make(AdSet $adSet, string $name, string $externalId): Ad
    {
        $ad = $this->adRepository->findByUserIdAndExternalId($adSet->getUserId(), $externalId);

        if ($ad === null) {
            $ad = new Ad(
                userId: $adSet->getUserId(),
                externalId: $externalId,
                adSetId: $adSet->getId(),
                campaignId: $adSet->getCampaignId(),
                name: $name,
                createdAt: $this->clock->now(),
                changedAt: $this->clock->now(),
            );

            $this->adRepository->save($ad);
        }

        return $ad;
    }
}
