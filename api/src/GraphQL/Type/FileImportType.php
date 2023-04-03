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
            'interfaces' => [$type->import()],
            'fields' => [
                $type->import()->getField('id'),
                $type->import()->getField('status'),
                $type->import()->getField('dataProvider'),
                $type->import()->getField('startedAt'),
                $type->import()->getField('completedAt'),
                'filename' => [
                    'type' => $type->string(),
                    'resolve' => fn (FileImport $fileImport) => $fileImport->getFilename(),
                ],
            ],
        ]);
    }
}
