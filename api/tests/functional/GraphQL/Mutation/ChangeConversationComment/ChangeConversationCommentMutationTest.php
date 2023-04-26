<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation\ChangeConversationComment;

use App\Entity\ConversationComment;
use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Mutation\ChangeConversationCommentMutation
 * @covers \App\Message\CommandHandler\ChangeConversationCommentHandler
 */
class ChangeConversationCommentMutationTest extends GraphQLTestCase
{
    public function test_change_comment(): void
    {
        $this->authenticate();

        $comment = $this->getLastComment();
        $mutation = <<<'EOF'
            mutation($commentId: ID!) {
              changeConversationComment(commentId: $commentId, comment: "This is some updated comment")
            }
            EOF;

        $this->executeMutation($mutation, ['commentId' => $comment->getId()]);
        $this->assertResponseMatchesPattern('{"data":{"changeConversationComment":"OK"}}');
        $this->assertResponseIsSuccessful();

        $comment = $this->getLastComment();
        $this->assertSame('This is some updated comment', $comment->getComment());
    }

    private function getLastComment(): ConversationComment
    {
        $comment = $this->getRepository(ConversationComment::class)->findOneBy([], ['id' => 'DESC']);
        $this->assertNotNull($comment);

        return $comment;
    }
}
