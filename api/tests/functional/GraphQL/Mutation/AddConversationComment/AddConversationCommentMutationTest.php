<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation\AddConversationComment;

use App\DataFixtures\Account1\ConversationFixture1;
use App\DataFixtures\Account2\ConversationFixture2;
use App\DataFixtures\UserFixture;
use App\Entity\Conversation;
use App\Entity\ConversationComment;
use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Mutation\StartConversationMutation
 * @covers \App\MessageHandler\Command\AddConversationCommentHandler
 */
class AddConversationCommentMutationTest extends GraphQLTestCase
{
    public function test_add_comment(): void
    {
        $this->authenticate();

        $conversationId = $this->getRepository(Conversation::class)->find(ConversationFixture1::CONVERSATION_1)?->getId();

        $mutation = <<<'EOF'
            mutation($var1: ID!) {
              addConversationComment(conversationId: $var1, comment: "This is some test comment")
            }
            EOF;

        $this->executeMutation($mutation, ['var1' => $conversationId]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesPattern('{"data":{"addConversationComment":"OK"}}');

        $comment = $this->getRepository(ConversationComment::class)->findOneBy([], ['id' => 'DESC']);
        $this->assertNotNull($comment);

        $this->assertSame('This is some test comment', $comment->getComment());
    }

    public function test_add_comment_to_other_users_conversation_in_another_account_is_not_allowed(): void
    {
        $this->authenticate(UserFixture::JANE);

        $conversationId = $this->getRepository(Conversation::class)->find(ConversationFixture2::CONVERSATION_2)?->getId();

        $mutation = <<<'EOF'
            mutation($var1: ID!) {
              addConversationComment(conversationId: $var1, comment: "This is some test comment")
            }
            EOF;

        $this->executeMutation($mutation, ['var1' => $conversationId]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/AddCommentNotAllowed.json');
    }
}
