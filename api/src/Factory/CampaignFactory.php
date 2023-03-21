<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Campaign;
use App\Repository\CampaignRepository;
use Psr\Clock\ClockInterface;

class CampaignFactory
{
    public function __construct(private CampaignRepository $campaignRepository, private ClockInterface $clock)
    {
    }

    public function make(int $userId, string $name, string $externalId): Campaign
    {
        $campaign = $this->campaignRepository->findByUserIdAndExternalId($userId, $externalId);

        if ($campaign === null) {
            $campaign = new Campaign(
                externalId: $externalId,
                userId: $userId,
                name: $name,
                dataProviderId: $this->clock->now(),
                createdAt: $this->clock->now(),
                changedAt: $this->clock->now()
            );

            $this->campaignRepository->save($campaign);
        }

        return $campaign;
    }
}
