<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\GraphQL\TypeRegistry;
use Brick\Money\Money;
use GraphQL\Type\Definition\ObjectType;

class MoneyType extends ObjectType
{
    public function __construct(TypeRegistry $type)
    {
        parent::__construct([
            'name' => 'Money',
            'fields' => [
                'amount' => [
                    'type' => $type->float(),
                    'resolve' => fn (Money $money) => $money->getAmount()->toFloat(),
                ],
                'currency' => [
                    'type' => $type->string(),
                    'resolve' => fn (Money $money) => $money->getCurrency()->getCurrencyCode(),
                ],
            ],
        ]);
    }
}
