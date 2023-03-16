<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\AdCollection;
use App\GraphQL\TypeRegistry;
use App\Repository\AdRepository;
use GraphQL\Type\Definition\ObjectType;

class AdCollectionType extends ObjectType
{
    public function __construct(TypeRegistry $type, AdRepository $adRepository)
    {
        parent::__construct([
            'name' => 'AdCollection',
            'fields' => [
                'id' => [
                    'type' => $type->id(),
                ],
                'name' => [
                    'type' => $type->string(),
                ],
                'ads' => [
                    'type' => $type->listOf($type->ad()),
                    'resolve' => fn (AdCollection $adCollection) => $adRepository->findAllByIds($adCollection->getAdsIds()),
                ],
            ],
        ]);
    }
}
