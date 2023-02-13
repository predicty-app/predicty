<?php

declare(strict_types=1);

namespace App\GraphQL;

use App\GraphQL\Query\MeQuery;
use GraphQL\Type\Definition\ObjectType;

class Query extends ObjectType
{
    public function __construct(private TypeLoader $type, MeQuery $me)
    {
        parent::__construct([
            'name' => 'Query',
            'fields' => [
                'ping' => [
                    'type' => $this->type->string(),
                    'resolve' => fn () => 'pong at '.time(),
                ],
                $me,
            ],
        ]);
    }
}
