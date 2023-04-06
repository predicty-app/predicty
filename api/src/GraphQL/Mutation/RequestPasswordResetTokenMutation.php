<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Extension\Messenger\HandleTrait;
use App\GraphQL\TypeRegistry;
use App\Message\Command\RequestPasswordResetToken;
use GraphQL\Type\Definition\FieldDefinition;

class RequestPasswordResetTokenMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(TypeRegistry $type)
    {
        parent::__construct([
            'name' => 'requestPasswordResetToken',
            'type' => $type->string(),
            'args' => [
                'username' => $type->nonNull($type->string()),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Request password reset',
        ]);
    }

    private function resolve(array $args): string
    {
        $this->handle(new RequestPasswordResetToken($args['username']));

        return 'OK';
    }
}
