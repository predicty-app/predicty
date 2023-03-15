<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\GraphQL\TypeRegistry;
use App\Repository\AdCollectionRepository;
use App\Repository\CampaignRepository;
use GraphQL\Type\Definition\ObjectType;

class DashboardType extends ObjectType
{
    public function __construct(TypeRegistry $type, CampaignRepository $campaignRepository, AdCollectionRepository $adCollectionRepository)
    {
        parent::__construct([
            'name' => 'Dashboard',
            'fields' => [
                'name' => [
                    'type' => $type->string(),
                    'resolve' => fn () => 'Default Dashboard',
                ],
                'campaigns' => [
                    'type' => $type->listOf($type->campaign()),
                    'args' => [
                        'limit' => [
                            'type' => $type->int(),
                            'defaultValue' => 10,
                        ],
                    ],
                    'resolve' => fn () => $campaignRepository->findAll(),
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
