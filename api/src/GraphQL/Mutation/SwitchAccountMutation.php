<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Extension\Messenger\HandleTrait;
use App\GraphQL\TypeRegistry;
use App\Message\Command\SwitchAccount;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\FieldDefinition;

class SwitchAccountMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(TypeRegistry $type, private CurrentUser $currentUser)
    {
        parent::__construct([
            'name' => 'switchAccount',
            'type' => $type->string(),
            'args' => [
                'accountId' => $type->int(),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Switch to another account',
        ]);
    }

    private function resolve(array $args): string
    {
        $this->handle(new SwitchAccount($this->currentUser->getId(), $args['accountId']));

        return 'OK';
    }
}
