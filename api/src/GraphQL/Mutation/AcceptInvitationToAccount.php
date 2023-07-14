<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Extension\Messenger\HandleTrait;
use App\GraphQL\TypeRegistry;
use App\Message\Command\AcceptInvitationToAccount as AcceptInvitationToAccountCommand;
use GraphQL\Type\Definition\FieldDefinition;

class AcceptInvitationToAccount extends FieldDefinition
{
    use HandleTrait;

    public function __construct(TypeRegistry $type)
    {
        parent::__construct([
            'name' => 'acceptInvitationToAccount',
            'type' => $type->string(),
            'args' => [
                'invitationId' => $type->id(),
                'acceptedTermsOfServiceVersion' => [
                    'type' => $type->int(),
                    'description' => 'User must provide the latest terms of service version number',
                ],
                'hasAgreedToNewsletter' => [
                    'type' => $type->boolean(),
                    'description' => 'User can agree to receive the newsletter',
                ],
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Accept invitation to account',
        ]);
    }

    private function resolve(array $args): string
    {
        $this->handle(
            new AcceptInvitationToAccountCommand(
                $args['invitationId'],
                $args['acceptedTermsOfServiceVersion'] ?? 0,
                $args['hasAgreedToNewsletter'] ?? false
            )
        );

        return 'OK';
    }
}
