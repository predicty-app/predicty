<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Extension\Messenger\HandleTrait;
use App\GraphQL\TypeRegistry;
use App\Message\Command\RegisterDataProvider;
use App\Service\User\CurrentUserService;
use GraphQL\Type\Definition\FieldDefinition;

class RegisterDataProviderMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(TypeRegistry $type, private CurrentUserService $currentUserService)
    {
        parent::__construct([
            'name' => 'registerDataProvider',
            'type' => $type->string(),
            'args' => [
                'oauthRefreshToken' => $type->nonNullString(),
                'type' => $type->nonNull($type->id()),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Register a new data provider. Returns "OK" on success',
        ]);
    }

    private function resolve(array $args): string
    {
        $this->handle(
            new RegisterDataProvider(
                $this->currentUserService->getId(),
                $args['type'],
                $args['oauthRefreshToken']
            )
        );

        return 'OK';
    }
}
