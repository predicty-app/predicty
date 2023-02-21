<?php

declare(strict_types=1);

namespace App\GraphQL;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema as GraphQLSchema;
use GraphQL\Type\SchemaConfig;

class Schema extends GraphQLSchema
{
    public function __construct(iterable $queries, iterable $mutations, TypeResolver $typeResolver)
    {
        $config = SchemaConfig::create()
            ->setQuery(new ObjectType([
                'name' => 'Query',
                'fields' => [...$queries],
            ]))
            ->setMutation(new ObjectType([
                'name' => 'Mutation',
                'fields' => [...$mutations],
            ]))
            ->setTypeLoader(fn (string $name) => $typeResolver->get($name))
        ;

        parent::__construct($config);
    }
}
