<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\Ad;
use App\GraphQL\TypeRegistry;
use App\Repository\AdInsightsRepository;
use GraphQL\Type\Definition\ObjectType;

class AdType extends ObjectType
{
    public function __construct(
        TypeRegistry $type,
        AdInsightsRepository $adInsightsRepository,
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
                'startedAt' => [
                    'type' => $type->date(),
                ],
                'endedAt' => [
                    'type' => $type->date(),
                ],
                'adStats' => [
                    'type' => $type->listOf($type->adInsights()),
                    'resolve' => fn (Ad $ad) => $adInsightsRepository->findAllByAdId($ad->getId()),
                    'deprecationReason' => 'Use `adInsights` instead.',
                ],
                'adInsights' => [
                    'type' => $type->listOf($type->adInsights()),
                    'resolve' => fn (Ad $ad) => $adInsightsRepository->findAllByAdId($ad->getId()),
                ],
            ],
        ]);
    }
}
