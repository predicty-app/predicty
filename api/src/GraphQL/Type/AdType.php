<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\Ad;
use App\GraphQL\TypeResolver;
use App\Repository\AdStatsRepository;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AdType extends ObjectType
{
    public function __construct(TypeResolver $types, AdStatsRepository $adStatsRepository)
    {
        parent::__construct([
            'name' => 'Ad',
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
                'adStats' => [
                    'type' => $types->listOf($types->adStats()),
                    'resolve' => fn (Ad $ad) => $adStatsRepository->findAllByAdId($ad->getId()),
                ],
            ],
        ]);
    }
}
