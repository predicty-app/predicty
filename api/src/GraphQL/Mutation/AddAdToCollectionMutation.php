<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Entity\AdCollection;
use App\Extension\Messenger\HandleTrait;
use App\GraphQL\TypeRegistry;
use App\Message\Command\AddAdToCollection;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\FieldDefinition;

class AddAdToCollectionMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(TypeRegistry $type, private CurrentUser $currentUser)
    {
        parent::__construct([
            'name' => 'addToAdCollection',
            'type' => $type->adCollection(),
            'args' => [
                'adCollectionId' => $type->nonNullId(),
                'adsIds' => $type->nonNull($type->listOf($type->id())),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Add ads to ad collection',
        ]);
    }

    private function resolve(array $args): AdCollection
    {
        return $this->handle(new AddAdToCollection(
            $this->currentUser->getId(),
            $this->currentUser->getAccountId(),
            (int) $args['adCollectionId'],
            array_map(fn (string $id) => (int) $id, $args['adsIds'])
        ));
    }
}
