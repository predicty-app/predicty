<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\Color;
use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;

class ColorType extends ObjectType
{
    public function __construct(TypeRegistry $type)
    {
        parent::__construct([
            'name' => 'Color',
            'fields' => [
                'hex' => [
                    'type' => $type->string(),
                    'resolve' => fn (Color $color) => $color->toHexString(),
                ],
                'rgb' => [
                    'type' => $type->string(),
                    'resolve' => fn (Color $color) => $color->toRGBString(),
                ],
            ],
        ]);
    }
}
