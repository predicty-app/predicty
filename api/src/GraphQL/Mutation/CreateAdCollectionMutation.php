<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Entity\AdCollection;
use App\Extension\Messenger\HandleTrait;
use App\GraphQL\TypeRegistry;
use App\Message\Command\CreateAdCollection;
use GraphQL\Type\Definition\FieldDefinition;

class CreateAdCollectionMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(TypeRegistry $type)
    {
        parent::__construct([
            'name' => 'createAdCollection',
            'type' => $type->adCollection(),
            'args' => [
                'name' => $type->string(),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Create an ad collection',
        ]);
    }

    private function resolve(array $args): AdCollection
    {
        return $this->handle(new CreateAdCollection($args['name']));
    }
}
