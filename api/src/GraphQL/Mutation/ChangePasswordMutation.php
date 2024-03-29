<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Extension\Messenger\HandleTrait;
use App\GraphQL\TypeRegistry;
use App\Message\Command\ChangePassword;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\FieldDefinition;

class ChangePasswordMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(TypeRegistry $type, private CurrentUser $currentUser)
    {
        parent::__construct([
            'name' => 'changePassword',
            'type' => $type->string(),
            'args' => [
                'oldPassword' => $type->nonNull($type->string()),
                'newPassword' => $type->nonNull($type->string()),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Change account password',
        ]);
    }

    private function resolve(array $args): string
    {
        $this->handle(new ChangePassword($this->currentUser->getId(), $args['oldPassword'], $args['newPassword']));

        return 'OK';
    }
}
