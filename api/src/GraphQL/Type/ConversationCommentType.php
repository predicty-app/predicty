<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\ConversationComment;
use App\Entity\Permission;
use App\GraphQL\TypeRegistry;
use App\Repository\UserRepository;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\ObjectType;

class ConversationCommentType extends ObjectType
{
    public function __construct(
        TypeRegistry $type,
        UserRepository $userRepository,
        CurrentUser $currentUser,
    ) {
        parent::__construct([
            'name' => 'ConversationComment',
            'fields' => [
                'id' => [
                    'type' => $type->id(),
                ],
                'createdAt' => [
                    'type' => $type->date(),
                ],
                'changedAt' => [
                    'type' => $type->date(),
                ],
                'user' => [
                    'type' => $type->user(),
                    'resolve' => fn (ConversationComment $comment) => $userRepository->findById($comment->getUserId()),
                ],
                'comment' => [
                    'type' => $type->string(),
                ],
                'isEditable' => [
                    'type' => $type->boolean(),
                    'resolve' => fn (ConversationComment $comment) => $currentUser->isAllowedTo(Permission::EDIT_CONVERSATION_COMMENT, $comment),
                ],
            ],
        ]);
    }
}
