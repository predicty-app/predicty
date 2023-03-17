<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\AdSet;
use App\GraphQL\TypeRegistry;
use App\Repository\AdRepository;
use App\Repository\AdSetRepository;
use GraphQL\Type\Definition\ObjectType;

class AdSetType extends ObjectType
{
    public function __construct(TypeRegistry $type, AdRepository $adRepository, AdSetRepository $adSetRepository)
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
                'startedAt' => [
                    'type' => $type->date(),
                    'resolve' => fn (AdSet $adSet) => $adSetRepository->findStartAndEndDate($adSet->getId())['start'],
                ],
                'endedAt' => [
                    'type' => $type->date(),
                    'resolve' => fn (AdSet $adSet) => $adSetRepository->findStartAndEndDate($adSet->getId())['end'],
                ],
            ],
        ]);
    }
}
