<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\Entity\Conversation;
use App\GraphQL\TypeRegistry;
use App\Repository\ConversationCommentRepository;
use App\Repository\UserRepository;
use App\Service\User\CurrentUserService;
use GraphQL\Type\Definition\ObjectType;

class ConversationType extends ObjectType
{
    public function __construct(
        TypeRegistry $type,
        UserRepository $userRepository,
        ConversationCommentRepository $conversationCommentRepository,
        CurrentUserService $currentUserService
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
                    'resolve' => fn (Conversation $conversation) => $currentUserService->isAnOwnerOf($conversation),
                ],
            ],
        ]);
    }
}
