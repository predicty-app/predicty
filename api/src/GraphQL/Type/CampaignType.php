<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\Campaign;
use App\GraphQL\TypeResolver;
use App\Repository\AdSetRepository;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class CampaignType extends ObjectType
{
    public function __construct(TypeResolver $types, AdSetRepository $adSetRepository)
    {
        parent::__construct([
            'name' => 'Campaign',
            'fields' => [
                'id' => [
                    'type' => Type::id(),
                ],
                'externalId' => [
                    'type' => Type::string(),
                ],
                'name' => [
                    'type' => Type::string(),
                ],
                'adSets' => [
                    'type' => Type::listOf($types->adSet()),
                    'resolve' => fn (Campaign $campaign) => $adSetRepository->findAllByCampaignId($campaign->getId()),
                ],
            ],
        ]);
    }
}
