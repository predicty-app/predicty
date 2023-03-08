<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\AdStats;
use App\GraphQL\TypeResolver;
use GraphQL\Type\Definition\ObjectType;

class AdStatsType extends ObjectType
{
    public function __construct(TypeResolver $types)
    {
        parent::__construct([
            'name' => 'AdStats',
            'fields' => [
                'id' => [
                    'type' => $types->id(),
                ],
                'results' => [
                    'type' => $types->int(),
                ],
                'costPerResult' => [
                    'type' => $types->money(),
                ],
                'amountSpent' => [
                    'type' => $types->money(),
                ],
                'date' => [
                    'type' => $types->string(),
                    'resolve' => fn (AdStats $adStats) => $adStats->getDate()->format('Y-m-d'),
                ],
            ],
        ]);
    }
}
