<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Extension\Messenger\HandleTrait;
use App\GraphQL\TypeRegistry;
use App\Message\Command\ResetPassword;
use GraphQL\Type\Definition\FieldDefinition;

class ResetPasswordMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(TypeRegistry $type)
    {
        parent::__construct([
            'name' => 'resetPassword',
            'type' => $type->string(),
            'args' => [
                'token' => $type->nonNull($type->string()),
                'password' => $type->nonNull($type->string()),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Reset password using token',
        ]);
    }

    private function resolve(array $args): string
    {
        $this->handle(new ResetPassword($args['token'], $args['password']));

        return 'OK';
    }
}
