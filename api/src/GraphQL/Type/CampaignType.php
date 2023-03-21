<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\Campaign;
use App\GraphQL\TypeRegistry;
use App\Repository\AdSetRepository;
use App\Repository\DataProviderRepository;
use GraphQL\Type\Definition\ObjectType;

class CampaignType extends ObjectType
{
    public function __construct(TypeRegistry $type, AdSetRepository $adSetRepository, DataProviderRepository $dataProviderRepository)
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
                'dataProvider' => [
                    'type' => $type->dataProvider(),
                    'resolve' => fn (Campaign $campaign) => $dataProviderRepository->findById($campaign->getDataProviderId()),
                ],
                'adSets' => [
                    'type' => $type->listOf($type->adSet()),
                    'resolve' => fn (Campaign $campaign) => $adSetRepository->findAllByCampaignId($campaign->getId()),
                ],
            ],
        ]);
    }
}
