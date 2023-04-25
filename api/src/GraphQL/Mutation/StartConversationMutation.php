<?php

declare(strict_types=1);

namespace App\GraphQL\Mutation;

use App\Extension\Messenger\HandleTrait;
use App\GraphQL\TypeRegistry;
use App\Message\Command\StartConversation;
use App\Service\User\CurrentUserService;
use GraphQL\Type\Definition\FieldDefinition;

class StartConversationMutation extends FieldDefinition
{
    use HandleTrait;

    public function __construct(TypeRegistry $type, private CurrentUserService $currentUserService)
    {
        parent::__construct([
            'name' => 'startConversation',
            'type' => $type->string(),
            'args' => [
                'color' => $type->string(),
                'comment' => $type->string(),
                'date' => $type->nonNull($type->date()),
            ],
            'resolve' => fn (mixed $root, array $args) => $this->resolve($args),
            'description' => 'Start a conversation',
        ]);
    }

    private function resolve(array $args): string
    {
        $this->handle(new StartConversation(
            $this->currentUserService->getId(),
            $args['date'],
            $args['comment'] ?? '',
            $args['color'] ?? '#ffffff',
        ));

        return 'OK';
    }
}
