<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use Brick\Money\Money;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class MoneyType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Money',
            'fields' => [
                'amount' => [
                    'type' => Type::float(),
                    'resolve' => fn (Money $money) => $money->getAmount()->toFloat(),
                ],
                'currency' => [
                    'type' => Type::string(),
                    'resolve' => fn (Money $money) => $money->getCurrency()->getCurrencyCode(),
                ],
            ],
        ]);
    }
}
