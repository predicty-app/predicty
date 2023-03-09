<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\GraphQL\TypeResolver;
use App\Repository\AdCollectionRepository;
use App\Repository\CampaignRepository;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class DashboardType extends ObjectType
{
    public function __construct(TypeResolver $types, CampaignRepository $campaignRepository, AdCollectionRepository $adCollectionRepository)
    {
        parent::__construct([
            'name' => 'Dashboard',
            'fields' => [
                'name' => [
                    'type' => Type::string(),
                    'resolve' => fn () => 'Default Dashboard',
                ],
                'campaigns' => [
                    'type' => Type::listOf($types->campaign()),
                    'args' => [
                        'limit' => [
                            'type' => $types->int(),
                            'defaultValue' => 10,
                        ],
                    ],
                    'resolve' => fn () => $campaignRepository->findAll(),
                ],
                'collections' => [
                    'type' => Type::listOf($types->adCollection()),
                    'args' => [
                        'limit' => [
                            'type' => $types->int(),
                            'defaultValue' => 10,
                        ],
                    ],
                    'resolve' => fn () => $adCollectionRepository->findAll(),
                ],
            ],
        ]);
    }
}
