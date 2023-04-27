<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Extension\Messenger\HandleTrait;
use App\GraphQL\TypeRegistry;
use App\Message\Command\ChangeConversationComment;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\FieldDefinition;

class ChangeConversationCommentMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(TypeRegistry $type, private CurrentUser $currentUser)
    {
        parent::__construct([
            'name' => 'changeConversationComment',
            'type' => $type->string(),
            'args' => [
                'commentId' => $type->nonNull($type->id()),
                'comment' => $type->nonNull($type->string()),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Change a previously added comment',
        ]);
    }

    private function resolve(array $args): string
    {
        $this->handle(new ChangeConversationComment(
            (int) $args['commentId'],
            $this->currentUser->getId(),
            $args['comment'],
        ));

        return 'OK';
    }
}
