<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\DataProviderType;
use GraphQL\Type\Definition\PhpEnumType;

class DataProviderTypeType extends PhpEnumType
{
    public function __construct()
    {
        parent::__construct(DataProviderType::class);
    }
}
