<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Extension\Messenger\HandleTrait;
use App\GraphQL\TypeRegistry;
use App\Message\Command\ChangeAccountRole;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\FieldDefinition;

class ChangeAccountRoleMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(TypeRegistry $type, private CurrentUser $currentUser)
    {
        parent::__construct([
            'name' => 'changeAccountRole',
            'type' => $type->string(),
            'args' => [
                'userId' => $type->nonNullId(),
                'role' => $type->nonNullString(),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Change another user\'s account role.',
        ]);
    }

    private function resolve(array $args): string
    {
        $this->handle(new ChangeAccountRole(
            $this->currentUser->getId(),
            $args['userId'],
            $this->currentUser->getAccountId(),
            $args['role'],
        ));

        return 'OK';
    }
}
