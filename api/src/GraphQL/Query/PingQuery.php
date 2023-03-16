<?php

declare(strict_types=1);

namespace App\GraphQL\Query;

use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\FieldDefinition;
use Psr\Clock\ClockInterface;

class PingQuery extends FieldDefinition
{
    public function __construct(TypeRegistry $type, ClockInterface $clock)
    {
        parent::__construct([
            'name' => 'ping',
            'fields' => [],
            'type' => $type->string(),
            'resolve' => fn () => 'pong at '.$clock->now()->getTimestamp(),
            'description' => 'Test method that will return a response containing current unix timestamp',
        ]);
    }
}
