<?php

declare(strict_types=1);

namespace App\GraphQL;

use App\GraphQL\Type\MutationType;
use App\GraphQL\Type\QueryType;
use GraphQL\Type\Schema as GraphQLSchema;
use GraphQL\Type\SchemaConfig;

class Schema extends GraphQLSchema
{
    public function __construct(TypeRegistry $typeResolver)
    {
        $config = SchemaConfig::create()
            ->setQuery($typeResolver->get(QueryType::class))
            ->setMutation($typeResolver->get(MutationType::class))
            /* @phpstan-ignore-next-line */
            ->setTypeLoader(fn (string $name) => $typeResolver->get($name))
        ;

        parent::__construct($config);
    }
}
