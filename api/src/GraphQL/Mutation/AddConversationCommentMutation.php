<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Extension\Messenger\HandleTrait;
use App\GraphQL\TypeRegistry;
use App\Message\Command\AddConversationComment;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\FieldDefinition;

class AddConversationCommentMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(TypeRegistry $type, private CurrentUser $currentUser)
    {
        parent::__construct([
            'name' => 'addConversationComment',
            'type' => $type->string(),
            'args' => [
                'conversationId' => $type->nonNullId(),
                'comment' => $type->nonNullString(),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Add a comment to a conversation',
        ]);
    }

    private function resolve(array $args): string
    {
        $this->handle(new AddConversationComment(
            (int) $args['conversationId'],
            $this->currentUser->getId(),
            $args['comment'],
        ));

        return 'OK';
    }
}
