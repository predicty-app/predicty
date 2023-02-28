<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\GraphQL\TypeResolver;
use App\Message\Command\Logout;
use GraphQL\Type\Definition\FieldDefinition;
use Symfony\Component\Messenger\MessageBusInterface;

class LogoutMutation extends FieldDefinition
{
    public function __construct(TypeResolver $type, private MessageBusInterface $commandBus)
    {
        parent::__construct([
            'name' => 'logout',
            'type' => $type->string(),
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Logout',
        ]);
    }

    private function resolve(array $args): string
    {
        $this->commandBus->dispatch(new Logout());

        return 'OK';
    }
}
