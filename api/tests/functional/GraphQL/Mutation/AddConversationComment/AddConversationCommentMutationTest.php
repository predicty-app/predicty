<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation\AddConversationComment;

use App\DataFixtures\UserFixture;
use App\Entity\ConversationComment;
use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Mutation\StartConversationMutation
 * @covers \App\MessageHandler\Command\StartConversationHandler
 */
class AddConversationCommentMutationTest extends GraphQLTestCase
{
    public function test_add_comment(): void
    {
        $this->authenticate();

        $mutation = <<<'EOF'
            mutation {
              addConversationComment(conversationId: 1, comment: "This is some test comment")
            }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesPattern('{"data":{"addConversationComment":"OK"}}');

        $comment = $this->getRepository(ConversationComment::class)->findOneBy([], ['id' => 'DESC']);
        $this->assertNotNull($comment);

        $this->assertSame('This is some test comment', $comment->getComment());
    }

    public function test_add_comment_to_other_users_conversation_is_not_allowed(): void
    {
        $this->authenticate(UserFixture::JANE);

        $mutation = <<<'EOF'
            mutation {
              addConversationComment(conversationId: 1, comment: "This is some test comment")
            }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/AddCommentNotAllowed.json');
    }
}
