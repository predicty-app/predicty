<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Entity\DataProviderType;
use App\GraphQL\TypeResolver;
use App\Message\Command\RegisterDataProvider;
use GraphQL\Type\Definition\FieldDefinition;
use GraphQL\Type\Definition\PhpEnumType;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class RegisterDataProviderMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(
        TypeResolver $type,
        private MessageBusInterface $messageBus
    ) {
        parent::__construct([
            'name' => 'registerDataProvider',
            'type' => $type->string(),
            'args' => [
                'oauthRefreshToken' => $type->nonNull($type->string()),
                'type' => $type->nonNull(new PhpEnumType(DataProviderType::class)),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Register a new data provider. Returns "OK" on success',
        ]);
    }

    private function resolve(array $args): string
    {
        $this->handle(new RegisterDataProvider($args['type'], $args['oauthRefreshToken']));

        return 'OK';
    }
}
