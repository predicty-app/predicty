<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\AdInsights;
use App\GraphQL\TypeRegistry;
use App\Repository\DailyRevenueRepository;
use GraphQL\Type\Definition\ObjectType;

class AdInsightsType extends ObjectType
{
    public function __construct(TypeRegistry $type, DailyRevenueRepository $dailyRevenueRepository)
    {
        parent::__construct([
            'name' => 'AdInsights',
            'fields' => [
                'id' => [
                    'type' => $type->id(),
                ],
                'results' => [
                    'type' => $type->int(),
                ],
                'costPerResult' => [
                    'type' => $type->money(),
                ],
                'amountSpent' => [
                    'type' => $type->money(),
                ],
                'revenueShare' => [
                    'type' => $type->money(),
                    'resolve' => fn (AdInsights $adInsights) => $dailyRevenueRepository->getDailyRevenueFor($adInsights),
                ],
                'date' => [
                    'type' => $type->string(),
                    'resolve' => fn (AdInsights $adInsights) => $adInsights->getDate()->format('Y-m-d'),
                ],
            ],
        ]);
    }
}
