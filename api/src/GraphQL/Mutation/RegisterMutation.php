<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\GraphQL\Mapper\UserMapper;
use App\GraphQL\TypeResolver;
use App\Message\Command\Register;
use GraphQL\Type\Definition\FieldDefinition;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class RegisterMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(
        TypeResolver $type,
        private UserMapper $userMapper,
        private MessageBusInterface $messageBus
    ) {
        parent::__construct([
            'name' => 'register',
            'type' => $type->string(),
            'args' => [
                'email' => $type->nonNull($type->string()),
                'password' => $type->string(),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Register a new account',
        ]);
    }

    private function resolve(array $args): string
    {
        $this->handle(new Register($args['email'], $args['password']));

        return 'OK';
    }
}
