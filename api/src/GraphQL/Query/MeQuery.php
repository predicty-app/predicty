<?php

declare(strict_types=1);

namespace App\GraphQL\Query;

use App\GraphQL\Resolver\MeResolver;
use App\GraphQL\TypeLoader;
use GraphQL\Type\Definition\FieldDefinition;

class MeQuery extends FieldDefinition
{
    public function __construct(MeResolver $resolver, TypeLoader $type)
    {
        parent::__construct([
            'name' => 'me',
            'type' => $type->user(),
            'args' => [
                'limit' => [
                    'type' => $type->int(),
                    'description' => 'Limit of sth',
                    'defaultValue' => 10
                ]
            ],
            'resolve' => fn () => $resolver->resolve(),
            'description' => 'Find currently logger in user',
        ]);
    }
}
