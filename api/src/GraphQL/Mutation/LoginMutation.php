<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Entity\User;
use App\GraphQL\Mapper\UserMapper;
use App\GraphQL\TypeResolver;
use App\Message\Command\Login;
use GraphQL\Type\Definition\FieldDefinition;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class LoginMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(
        TypeResolver $type,
        private MessageBusInterface $messageBus,
        private UserMapper $userMapper
    ) {
        parent::__construct([
            'name' => 'login',
            'type' => $type->user(),
            'args' => [
                'username' => $type->nonNull($type->string()),
                'password' => $type->nonNull($type->string()),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Log into an account',
        ]);
    }

    private function resolve(array $args): array
    {
        $user = $this->handle(new Login($args['username'], $args['password']));
        assert($user instanceof User);

        return $this->userMapper->toArray($user);
    }
}
