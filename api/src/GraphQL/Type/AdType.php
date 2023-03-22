<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\Ad;
use App\GraphQL\TypeRegistry;
use App\Repository\AdStatsRepository;
use GraphQL\Type\Definition\ObjectType;

class AdType extends ObjectType
{
    public function __construct(
        TypeRegistry $type,
        AdStatsRepository $adStatsRepository,
    ) {
        parent::__construct([
            'name' => 'Ad',
            'fields' => [
                'id' => [
                    'type' => $type->id(),
                ],
                'externalId' => [
                    'type' => $type->string(),
                ],
                'name' => [
                    'type' => $type->string(),
                ],
                'campaignId' => [
                    'type' => fn () => $type->id(),
                    'resolve' => fn (Ad $ad) => $ad->getCampaignId(),
                ],
                'adStats' => [
                    'type' => $type->listOf($type->adStats()),
                    'resolve' => fn (Ad $ad) => $adStatsRepository->findAllByAdId($ad->getId()),
                ],
            ],
        ]);
    }
}
