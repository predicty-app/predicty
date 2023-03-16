<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\AdSet;
use App\GraphQL\TypeRegistry;
use App\Repository\AdRepository;
use GraphQL\Type\Definition\ObjectType;

class AdSetType extends ObjectType
{
    public function __construct(TypeRegistry $type, AdRepository $adRepository)
    {
        parent::__construct([
            'name' => 'AdSet',
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
                'ads' => [
                    'type' => $type->listOf($type->ad()),
                    'resolve' => fn (AdSet $adSet) => $adRepository->findAllByAdSetId($adSet->getId()),
                ],
            ],
        ]);
    }
}
