<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation\StartConversation;

use App\Entity\Conversation;
use App\Entity\ConversationComment;
use App\Service\Util\DateHelper;
use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Mutation\StartConversationMutation
 * @covers \App\MessageHandler\Command\StartConversationHandler
 */
class StartConversationMutationTest extends GraphQLTestCase
{
    public function test_start_conversation(): void
    {
        $this->authenticate();

        $mutation = <<<'EOF'
            mutation {
              startConversation(date: "2023-01-08", comment: "Some comment", color: "#FFFFFF")
            }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesPattern('{"data":{"startConversation":"OK"}}');

        $conversation = $this->getRepository(Conversation::class)->findOneBy(['date' => DateHelper::fromString('2023-01-08')]);
        $this->assertNotNull($conversation);

        $comment = $this->getRepository(ConversationComment::class)->findOneBy(['conversationId' => $conversation->getId()]);
        $this->assertNotNull($comment);

        $this->assertSame('Some comment', $comment->getComment());
    }

    public function test_start_multiple_conversation_on_different_days(): void
    {
        $this->authenticate();

        $mutation = <<<'EOF'
            mutation {
              startConversation(date: "2023-01-08", comment: "Some comment", color: "#FFFFFF")
            }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesPattern('{"data":{"startConversation":"OK"}}');

        $mutation = <<<'EOF'
            mutation {
              startConversation(date: "2023-01-09", comment: "Some comment", color: "#FFFFFF")
            }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesPattern('{"data":{"startConversation":"OK"}}');
    }

    public function test_mistakenly_starting_conversation_for_the_same_day_twice_adds_only_a_comment(): void
    {
        $this->authenticate();

        $mutation = <<<'EOF'
            mutation {
              startConversation(date: "2023-01-08", comment: "Some comment", color: "#FFFFFF")
            }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseMatchesPattern('{"data":{"startConversation":"OK"}}');

        $mutation = <<<'EOF'
            mutation {
              startConversation(date: "2023-01-08", comment: "Some other comment", color: "#000000")
            }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseMatchesPattern('{"data":{"startConversation":"OK"}}');

        $conversation = $this->getRepository(Conversation::class)->findOneBy(['date' => DateHelper::fromString('2023-01-08')]);
        $this->assertNotNull($conversation);
        $this->assertSame('#ffffff', $conversation->getColor()->toHexString());

        $comments = $this->getRepository(ConversationComment::class)->findBy(['conversationId' => $conversation->getId()], ['id' => 'ASC']);
        $this->assertCount(2, $comments);

        $this->assertSame('Some comment', $comments[0]->getComment());
        $this->assertSame('Some other comment', $comments[1]->getComment());
    }
}
