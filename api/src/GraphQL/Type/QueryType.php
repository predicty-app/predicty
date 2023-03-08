<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;

class QueryType extends ObjectType
{
    public function __construct(iterable $rootQueryFields)
    {
        parent::__construct([
            'name' => 'Query',
            'fields' => [...$rootQueryFields],
        ]);
    }
}
