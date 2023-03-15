<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\DataProvider;
use GraphQL\Type\Definition\PhpEnumType;

class DataProviderIdType extends PhpEnumType
{
    public function __construct()
    {
        parent::__construct(DataProvider::class);
        $this->name = 'DataProviderId';
    }
}
