<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\ConnectedAccount;
use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;

class ConnectedAccountType extends ObjectType
{
    public function __construct(TypeRegistry $type)
    {
        parent::__construct([
            'name' => 'ConnectedAccount',
            'fields' => [
                'id' => [
                    'type' => $type->id(),
                ],
                'dataProvider' => [
                    'type' => $type->dataProvider(),
                ],
                'isEnabled' => [
                    'type' => $type->boolean(),
                ],
                'connectedAt' => [
                    'type' => $type->dateTime(),
                    'resolve' => fn (ConnectedAccount $connectedAccount) => $connectedAccount->getCreatedAt(),
                ],
            ],
        ]);
    }
}
