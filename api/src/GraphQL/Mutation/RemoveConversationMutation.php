<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Extension\Messenger\HandleTrait;
use App\GraphQL\TypeRegistry;
use App\Message\Command\RemoveConversation;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\FieldDefinition;

class RemoveConversationMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(TypeRegistry $type, private CurrentUser $currentUser)
    {
        parent::__construct([
            'name' => 'removeConversation',
            'type' => $type->string(),
            'args' => [
                'conversationId' => $type->nonNull($type->id()),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Remove a conversation',
        ]);
    }

    private function resolve(array $args): string
    {
        $this->handle(new RemoveConversation((int) $args['conversationId'], $this->currentUser->getId()));

        return 'OK';
    }
}
