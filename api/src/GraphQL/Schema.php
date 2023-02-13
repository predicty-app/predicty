<?php

declare(strict_types=1);

namespace App\GraphQL;

use GraphQL\Type\Schema as GraphQLSchema;
use GraphQL\Type\SchemaConfig;

class Schema extends GraphQLSchema
{
    public function __construct(Query $query, Mutation $mutation, TypeLoader $typeLoader)
    {
        $config = SchemaConfig::create()
            ->setQuery($query)
            ->setMutation($mutation)
            ->setTypeLoader($typeLoader)
        ;

        parent::__construct($config);
    }
}
