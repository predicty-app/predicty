<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\AdCollection;
use App\GraphQL\TypeResolver;
use App\Repository\AdRepository;
use GraphQL\Type\Definition\ObjectType;

class AdCollectionType extends ObjectType
{
    public function __construct(TypeResolver $types, AdRepository $adRepository)
    {
        parent::__construct([
            'name' => 'AdCollection',
            'fields' => [
                'id' => [
                    'type' => $types->id(),
                ],
                'name' => [
                    'type' => $types->string(),
                ],
                'ads' => [
                    'type' => $types->listOf($types->ad()),
                    'resolve' => fn (AdCollection $adCollection) => $adRepository->findAllByIds($adCollection->getAdsIds()),
                ],
            ],
        ]);
    }
}
