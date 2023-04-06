<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;

class ImportResultType extends ObjectType
{
    public function __construct(TypeRegistry $type)
    {
        parent::__construct([
            'name' => 'ImportResult',
            'fields' => [
                'createdCampaigns' => fn () => $type->int(),
                'createdAdSets' => fn () => $type->int(),
                'createdAds' => fn () => $type->int(),
                'createdAdStats' => fn () => $type->int(),
                'createdDailyRevenues' => fn () => $type->int(),
                'totalCreated' => fn () => $type->int(),
            ],
        ]);
    }
}
