<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\DataProvider;
use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;

class DataProviderType extends ObjectType
{
    public function __construct(TypeRegistry $type)
    {
        parent::__construct([
            'name' => 'DataProvider',
            'fields' => [
                'id' => [
                    'type' => $type->id(),
                    'resolve' => fn (DataProvider $dataProvider) => $dataProvider->getId(),
                ],
                'name' => $type->string(),
                'fileImportTypes' => [
                    'type' => $type->listOf($type->fileImportType()),
                    'resolve' => fn (DataProvider $dataProvider) => $dataProvider->getFileImportTypes(),
                ],
                'type' => $type->dataProviderType(),
                'createdAt' => $type->date(),
            ],
        ]);
    }
}
