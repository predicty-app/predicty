<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Extension\Messenger\HandleTrait;
use App\GraphQL\TypeRegistry;
use App\Message\Command\WithdrawImport;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\FieldDefinition;

class WithdrawImportMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(TypeRegistry $type, private CurrentUser $currentUser)
    {
        parent::__construct([
            'name' => 'withdrawImport',
            'type' => $type->string(),
            'args' => [
                'importId' => $type->nonNullId(),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Withdraw import and remove all data associated with it.',
        ]);
    }

    private function resolve(array $args): string
    {
        $this->handle(new WithdrawImport(
            $this->currentUser->getId(),
            $args['importId'],
        ));

        return 'OK';
    }
}
