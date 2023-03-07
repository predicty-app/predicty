<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\AdSet;
use App\Entity\Campaign;
use App\Repository\AdSetRepository;
use Psr\Clock\ClockInterface;

class AdSetFactory
{
    public function __construct(private AdSetRepository $adSetRepository, private ClockInterface $clock)
    {
    }

    public function make(Campaign $campaign, string $name, string $externalId): AdSet
    {
        $adSet = $this->adSetRepository->findByCampaignIdAndExternalId(
            $campaign->getUserId(),
            $campaign->getId(),
            $externalId
        );

        if ($adSet === null) {
            $adSet = new AdSet(
                externalId: $externalId,
                userId: $campaign->getUserId(),
                campaignId: $campaign->getId(),
                name: $name,
                createdAt: $this->clock->now(),
                changedAt: $this->clock->now()
            );
            $this->adSetRepository->save($adSet);
        }

        return $adSet;
    }
}
