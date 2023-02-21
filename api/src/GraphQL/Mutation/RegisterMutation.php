<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\GraphQL\TypeResolver;
use App\Message\Register;
use GraphQL\Type\Definition\FieldDefinition;
use Symfony\Component\Messenger\MessageBusInterface;

class RegisterMutation extends FieldDefinition
{
    public function __construct(TypeResolver $type, private MessageBusInterface $commandBus)
    {
        parent::__construct([
            'name' => 'register',
            'type' => $type->string(),
            'args' => [
                'email' => $type->nonNull($type->string()),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Register a new account',
        ]);
    }

    private function resolve(array $args): string
    {
        $this->commandBus->dispatch(new Register($args['email']));

        return 'OK';
    }
}
