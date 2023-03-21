<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\DataProviderType as DataProviderTypeAlias;
use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\PhpEnumType;

class DataProviderTypeType extends PhpEnumType
{
    public function __construct(TypeRegistry $type)
    {
        parent::__construct(DataProviderTypeAlias::class);
    }
}
