<?php

namespace App\GraphQL\Query;

use App\GraphQL\TypeResolver;
use GraphQL\Type\Definition\FieldDefinition;

class PingQuery extends FieldDefinition
{
    public function __construct(TypeResolver $type)
    {
        parent::__construct([
            'name' => 'ping',
            'fields' => [],
            'type' => $type->string(),
            'resolve' => fn () => 'pong at '.time(),
        ]);
    }
}