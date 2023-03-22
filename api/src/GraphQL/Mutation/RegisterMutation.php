<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Extension\Messenger\HandleTrait;
use App\GraphQL\TypeRegistry;
use App\Message\Command\Register;
use GraphQL\Type\Definition\FieldDefinition;

class RegisterMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(TypeRegistry $type)
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
        $this->handle(new Register($args['email']));

        return 'OK';
    }
}
