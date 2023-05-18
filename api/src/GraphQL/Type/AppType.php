<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;

class AppType extends ObjectType
{
    public function __construct(TypeRegistry $type)
    {
        parent::__construct([
            'name' => 'App',
            'fields' => [
                'version' => [
                    'type' => $type->string(),
                    'resolve' => fn () => '0.1.0',
                ],
                'currentTermsOfServiceVersion' => [
                    'type' => $type->int(),
                ],
            ],
        ]);
    }
}
