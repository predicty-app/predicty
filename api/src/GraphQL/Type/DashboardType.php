<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\GraphQL\TypeRegistry;
use App\Repository\AdCollectionRepository;
use App\Repository\CampaignRepository;
use App\Repository\DailyRevenueRepository;
use GraphQL\Type\Definition\ObjectType;

class DashboardType extends ObjectType
{
    public function __construct(
        TypeRegistry $type,
        CampaignRepository $campaignRepository,
        AdCollectionRepository $adCollectionRepository,
        DailyRevenueRepository $dailyRevenueRepository
    ) {
        parent::__construct([
            'name' => 'Dashboard',
            'fields' => [
                'name' => [
                    'type' => $type->string(),
                    'resolve' => fn () => 'Default Dashboard',
                ],
                'dailyRevenue' => [
                    'type' => $type->listOf($type->dailyRevenue()),
                    'resolve' => fn () => $dailyRevenueRepository->findAll(),
                ],
                'campaigns' => [
                    'type' => $type->listOf($type->campaign()),
                    'args' => [
                        'limit' => [
                            'type' => $type->int(),
                            'defaultValue' => 10,
                        ],
                        'id' => [
                            'type' => $type->id(),
                            'defaultValue' => '',
                        ],
                    ],
                    'resolve' => function (mixed $dashboard, array $args) use ($campaignRepository) {
                        if ($args['id'] !== '') {
                            return [$campaignRepository->findById((int) $args['id'])];
                        }

                        return $campaignRepository->findAll((int) $args['limit']);
                    },
                ],
                'collections' => [
                    'type' => $type->listOf($type->adCollection()),
                    'args' => [
                        'limit' => [
                            'type' => $type->int(),
                            'defaultValue' => 10,
                        ],
                    ],
                    'resolve' => fn () => $adCollectionRepository->findAll(),
                ],
            ],
        ]);
    }
}
