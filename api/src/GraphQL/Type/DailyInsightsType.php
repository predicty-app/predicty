<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\DailyInsights;
use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;

class DailyInsightsType extends ObjectType
{
    public function __construct(TypeRegistry $type)
    {
        parent::__construct([
            'name' => 'DailyInsights',
            'fields' => [
                'date' => [
                    'type' => $type->date(),
                ],
                'results' => [
                    'type' => $type->int(),
                ],
                'costPerResult' => [
                    'type' => $type->float(),
                    'resolve' => fn (DailyInsights $insights) => $insights->getCostPerResult()->getAmount()->toFloat(),
                ],
                'amountSpent' => [
                    'type' => $type->float(),
                    'resolve' => fn (DailyInsights $insights) => $insights->getAmountSpent()->getAmount()->toFloat(),
                ],
                'revenue' => [
                    'type' => $type->float(),
                    'resolve' => fn (DailyInsights $insights) => $insights->getRevenue()->getAmount()->toFloat(),
                ],
                'averageOrderValue' => [
                    'type' => $type->float(),
                    'resolve' => fn (DailyInsights $insights) => $insights->getAverageOrderValue()->getAmount()->toFloat(),
                ],
                'clicks' => [
                    'type' => $type->int(),
                    'resolve' => fn () => 0,
                ],
                'impressions' => [
                    'type' => $type->int(),
                    'resolve' => fn () => 0,
                ],
                'costPerClick' => [
                    'type' => $type->float(),
                    'resolve' => fn () => 0,
                ],
                'costPerMil' => [
                    'type' => $type->float(),
                    'resolve' => fn () => 0,
                ],
            ],
        ]);
    }
}
