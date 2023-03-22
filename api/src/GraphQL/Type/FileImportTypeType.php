<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\FileImportType as EntityFileImportType;
use GraphQL\Type\Definition\PhpEnumType;

class FileImportTypeType extends PhpEnumType
{
    public function __construct()
    {
        parent::__construct(EntityFileImportType::class);
    }
}
