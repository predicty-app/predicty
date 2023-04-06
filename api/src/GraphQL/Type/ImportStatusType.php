<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\ImportStatus;
use GraphQL\Type\Definition\PhpEnumType;

class ImportStatusType extends PhpEnumType
{
    public function __construct()
    {
        parent::__construct(ImportStatus::class);
    }
}
