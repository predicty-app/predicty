<?php

declare(strict_types=1);

namespace App\GraphQL\Query;

use App\Entity\DataProvider;
use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\FieldDefinition;

class DataProvidersQuery extends FieldDefinition
{
    public function __construct(TypeRegistry $type)
    {
        parent::__construct([
            'name' => 'dataProviders',
            'type' => $type->listOf($type->dataProvider()),
            'resolve' => fn () => DataProvider::cases(),
            'description' => 'Get all supported data providers',
        ]);
    }
}
