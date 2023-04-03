<?php

declare(strict_types=1);

namespace App\GraphQL\Query;

use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\FieldDefinition;

class ImportsQuery extends FieldDefinition
{
    public function __construct(TypeRegistry $type)
    {
        parent::__construct([
            'name' => 'imports',
            'type' => $type->listOf($type->import()),
            'resolve' => fn () => [],
            'description' => 'List all imports',
        ]);
    }
}
