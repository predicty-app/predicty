<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Extension\Messenger\HandleTrait;
use App\GraphQL\TypeRegistry;
use App\Message\Command\InviteToAccount as InviteToAccountCommand;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\FieldDefinition;

class InviteToAccount extends FieldDefinition
{
    use HandleTrait;

    public function __construct(TypeRegistry $type, private CurrentUser $currentUser)
    {
        parent::__construct([
            'name' => 'inviteToAccount',
            'type' => $type->string(),
            'args' => [
                'email' => $type->string(),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Invite user to account',
        ]);
    }

    private function resolve(array $args): string
    {
        $this->handle(
            new InviteToAccountCommand(
                $this->currentUser->getId(),
                $this->currentUser->getAccountId(),
                $args['email']
            )
        );

        return 'OK';
    }
}
