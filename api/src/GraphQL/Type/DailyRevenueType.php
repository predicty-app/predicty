<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\DailyRevenue;
use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;

class DailyRevenueType extends ObjectType
{
    public function __construct(TypeRegistry $type)
    {
        parent::__construct([
            'name' => 'DailyRevenue',
            'fields' => [
                'id' => [
                    'type' => $type->id(),
                ],
                'revenue' => [
                    'type' => $type->money(),
                ],
                'averageOrderValue' => [
                    'type' => $type->money(),
                ],
                'date' => [
                    'type' => $type->date(),
                    'resolve' => fn (DailyRevenue $dailyRevenue) => $dailyRevenue->getDate()->format('Y-m-d'),
                ],
            ],
        ]);
    }
}
