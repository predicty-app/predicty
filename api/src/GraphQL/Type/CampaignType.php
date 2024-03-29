<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\Campaign;
use App\GraphQL\TypeRegistry;
use App\Repository\AdSetRepository;
use GraphQL\Type\Definition\ObjectType;

class CampaignType extends ObjectType
{
    public function __construct(TypeRegistry $type, AdSetRepository $adSetRepository)
    {
        parent::__construct([
            'name' => 'Campaign',
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
                'startedAt' => [
                    'type' => $type->date(),
                ],
                'endedAt' => [
                    'type' => $type->date(),
                ],
                'dataProvider' => [
                    'type' => $type->dataProvider(),
                    'resolve' => fn (Campaign $campaign) => $campaign->getDataProvider(),
                ],
                'adSets' => [
                    'type' => $type->listOf($type->adSet()),
                    'resolve' => fn (Campaign $campaign) => $adSetRepository->findAllByCampaignId($campaign->getId()),
                ],
            ],
        ]);
    }
}
