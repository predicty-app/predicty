<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\FileImport;
use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;

class FileImportType extends ObjectType
{
    public function __construct(TypeRegistry $type)
    {
        parent::__construct([
            'name' => 'FileImport',
            'description' => 'Represents a file import',
            'interfaces' => [$type->import()],
            'fields' => $type->import()->getFields() + [
                'filename' => [
                    'type' => $type->string(),
                    'resolve' => fn (FileImport $fileImport) => $fileImport->getFilename(),
                ],
            ],
        ]);
    }
}
