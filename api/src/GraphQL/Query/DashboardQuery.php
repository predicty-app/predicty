<?php

declare(strict_types=1);

namespace App\GraphQL\Query;

use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\FieldDefinition;

class DashboardQuery extends FieldDefinition
{
    public function __construct(TypeRegistry $type)
    {
        parent::__construct([
            'name' => 'dashboard',
            'type' => $type->dashboard(),
            'resolve' => fn () => [],
            'description' => 'Get current dashboard',
        ]);
    }
}
