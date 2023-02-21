<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Entity\User;
use App\GraphQL\Mapper\UserMapper;
use App\GraphQL\Resolver\UserResolver;
use App\GraphQL\TypeResolver;
use App\Message\Login;
use App\Message\Register;
use GraphQL\Type\Definition\FieldDefinition;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class LoginMutation extends FieldDefinition
{
    public function __construct(TypeResolver $type, private MessageBusInterface $commandBus, private UserMapper $userMapper)
    {
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
        $envelope = $this->commandBus->dispatch(new Login($args['username'], $args['password']));

        /** @var User $user */
        $user = $envelope->last(HandledStamp::class)->getResult();

        return $this->userMapper->toArray($user);
    }
}
