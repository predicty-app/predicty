<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\ApiImport;
use App\Entity\FileImport;
use App\Entity\Import;
use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\ObjectType;
use InvalidArgumentException;

class ImportType extends InterfaceType
{
    public function __construct(TypeRegistry $type)
    {
        parent::__construct([
            'name' => 'Import',
            'fields' => [
                'id' => [
                    'type' => $type->id(),
                ],
                'status' => [
                    'type' => $type->importStatus(),
                ],
                'message' => [
                    'type' => $type->string(),
                ],
                'dataProvider' => [
                    'type' => fn () => $type->dataProvider(),
                ],
                'startedAt' => [
                    'type' => $type->dateTime(),
                ],
                'completedAt' => [
                    'type' => $type->dateTime(),
                ],
            ],
            'resolveType' => function (Import $value) use ($type): ObjectType {
                return match (true) {
                    $value instanceof ApiImport => $type->apiImport(),
                    $value instanceof FileImport => $type->fileImport(),
                    default => throw new InvalidArgumentException('Unexpected type: '.$value::class)
                };
            },
        ]);
    }
}
