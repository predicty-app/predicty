<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Entity\DataProvider;
use App\Extension\Messenger\HandleTrait;
use App\GraphQL\TypeRegistry;
use App\Message\Command\CreateCustomDataProvider;
use App\Service\User\CurrentUserService;
use GraphQL\Type\Definition\FieldDefinition;

class CreateDataProviderMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(private CurrentUserService $currentUserService, TypeRegistry $type)
    {
        parent::__construct([
            'name' => 'createDataProvider',
            'type' => $type->dataProvider(),
            'args' => [
                'name' => $type->nonNull($type->string()),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Create a data provider',
        ]);
    }

    private function resolve(array $args): DataProvider
    {
        return $this->handle(
            new CreateCustomDataProvider(
            userId: $this->currentUserService->getId(),
            name: $args['name']
        )
        );
    }
}
