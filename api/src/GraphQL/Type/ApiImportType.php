<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;

class ApiImportType extends ObjectType
{
    public function __construct(TypeRegistry $type)
    {
        parent::__construct([
            'name' => 'ApiImport',
            'description' => 'Represents an API import',
            'interfaces' => [$type->import()],
            'fields' => $type->import()->getFields(),
        ]);
    }
}
