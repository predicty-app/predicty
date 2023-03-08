<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\AdSet;
use App\GraphQL\TypeResolver;
use App\Repository\AdRepository;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AdSetType extends ObjectType
{
    public function __construct(TypeResolver $types, AdRepository $adRepository)
    {
        parent::__construct([
            'name' => 'AdSet',
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
                'ads' => [
                    'type' => $types->listOf($types->ad()),
                    'resolve' => fn (AdSet $adSet) => $adRepository->findAllByAdSetId($adSet->getId()),
                ],
            ],
        ]);
    }
}
