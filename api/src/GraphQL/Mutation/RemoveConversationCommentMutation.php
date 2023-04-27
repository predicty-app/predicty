<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Extension\Messenger\HandleTrait;
use App\GraphQL\TypeRegistry;
use App\Message\Command\RemoveConversationComment;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\FieldDefinition;

class RemoveConversationCommentMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(TypeRegistry $type, private CurrentUser $currentUser)
    {
        parent::__construct([
            'name' => 'removeConversationComment',
            'type' => $type->string(),
            'args' => [
                'commentId' => $type->nonNull($type->id()),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Remove a previously added comment',
        ]);
    }

    private function resolve(array $args): string
    {
        $this->handle(new RemoveConversationComment((int) $args['commentId'], $this->currentUser->getId()));

        return 'OK';
    }
}
