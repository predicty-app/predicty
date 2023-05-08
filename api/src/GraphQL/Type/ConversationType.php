<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\Conversation;
use App\Entity\Permission;
use App\GraphQL\TypeRegistry;
use App\Repository\ConversationCommentRepository;
use App\Repository\UserRepository;
use App\Service\Security\CurrentUser;
use GraphQL\Type\Definition\ObjectType;

class ConversationType extends ObjectType
{
    public function __construct(
        TypeRegistry $type,
        UserRepository $userRepository,
        ConversationCommentRepository $conversationCommentRepository,
        CurrentUser $currentUser
    ) {
        parent::__construct([
            'name' => 'Conversation',
            'fields' => [
                'id' => [
                    'type' => $type->id(),
                ],
                'user' => [
                    'type' => $type->user(),
                    'resolve' => fn (Conversation $conversation) => $userRepository->findById($conversation->getUserId()),
                ],
                'date' => [
                    'type' => $type->date(),
                ],
                'color' => [
                    'type' => $type->color(),
                ],
                'comments' => [
                    'type' => $type->listOf($type->conversationComment()),
                    'resolve' => fn (Conversation $conversation) => $conversationCommentRepository->findByConversationId($conversation->getId()),
                ],
                'isRemovable' => [
                    'type' => $type->boolean(),
                    'resolve' => fn (Conversation $conversation) => $currentUser->isAllowedTo(Permission::REMOVE_CONVERSATION, $conversation),
                ],
            ],
        ]);
    }
}
