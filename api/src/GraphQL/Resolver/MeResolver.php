<?php

declare(strict_types=1);

namespace App\GraphQL\Resolver;

class MeResolver
{
    public function __construct()
    {
    }

    public function resolve(): array
    {
        return [
            'uuid' => '1234',
            'email' => 'john.doe@example.com',
        ];
    }
}
