<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\AdStats;
use App\GraphQL\TypeRegistry;
use App\Repository\DailyRevenueRepository;
use GraphQL\Type\Definition\ObjectType;

class AdStatsType extends ObjectType
{
    public function __construct(TypeRegistry $type, DailyRevenueRepository $dailyRevenueRepository)
    {
        parent::__construct([
            'name' => 'AdStats',
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
                    'resolve' => fn (AdStats $adStats) => $dailyRevenueRepository->getDailyRevenueFor($adStats),
                ],
                'date' => [
                    'type' => $type->string(),
                    'resolve' => fn (AdStats $adStats) => $adStats->getDate()->format('Y-m-d'),
                ],
            ],
        ]);
    }
}
