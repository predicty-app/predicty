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
                    'type' => $type->nonNull($type->dataProviderId()),
                    'resolve' => fn (DataProvider $dataProvider) => $dataProvider,
                ],
                'name' => $type->string(),
                'fileImportTypes' => [
                    'type' => $type->listOf($type->fileImportType()),
                    'resolve' => fn (DataProvider $dataProvider) => $dataProvider->getFileImportTypes(),
                ],
            ],
        ]);
    }
}
