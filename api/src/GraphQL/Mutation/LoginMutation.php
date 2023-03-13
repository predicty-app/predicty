<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Entity\User;
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
    ) {
        parent::__construct([
            'name' => 'login',
            'type' => $type->user(),
            'args' => [
                'username' => $type->nonNull($type->string()),
                'passcode' => $type->nonNull($type->string()),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Log into an account using one-time passcode',
        ]);
    }

    private function resolve(array $args): User
    {
        return $this->handle(new Login($args['username'], $args['passcode']));
    }
}
