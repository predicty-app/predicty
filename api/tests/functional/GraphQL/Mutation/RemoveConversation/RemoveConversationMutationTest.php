<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation\RemoveConversation;

use App\Entity\Conversation;
use App\Entity\ConversationComment;
use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Mutation\RemoveConversationMutation
 * @covers \App\MessageHandler\Command\RemoveConversationHandler
 */
class RemoveConversationMutationTest extends GraphQLTestCase
{
    public function test_remove_conversation(): void
    {
        $this->authenticate();
        $conversation = $this->getRepository(Conversation::class)->findOneBy([], ['id' => 'DESC']);
        $conversationId = $conversation?->getId();
        $this->assertNotNull($conversation);

        $mutation = <<<'EOF'
            mutation($conversationId: ID!) {
              removeConversation(conversationId: $conversationId)
            }
            EOF;

        $this->executeMutation($mutation, ['conversationId' => $conversationId]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesPattern('{"data":{"removeConversation":"OK"}}');

        $this->assertNull($this->getRepository(Conversation::class)->find($conversationId));
        $this->assertEmpty($this->getRepository(ConversationComment::class)->findBy(['conversationId' => $conversationId]));
    }
}
