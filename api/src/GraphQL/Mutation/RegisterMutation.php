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
                'acceptedTermsOfServiceVersion' => [
                    'type' => $type->int(),
                    'description' => 'User must provide the latest terms of service version number',
                ],
                'hasAgreedToNewsletter' => [
                    'type' => $type->boolean(),
                    'description' => 'User must agree to receive the newsletter',
                ],
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Register a new account',
        ]);
    }

    private function resolve(array $args): string
    {
        $args['acceptedTermsOfServiceVersion'] ??= 0;
        $args['hasAgreedToNewsletter'] ??= false;

        $this->handle(new Register(
            email: $args['email'],
            acceptedTermsOfServiceVersion: $args['acceptedTermsOfServiceVersion'],
            hasAgreedToNewsletter: $args['hasAgreedToNewsletter']
        ));

        return 'OK';
    }
}
