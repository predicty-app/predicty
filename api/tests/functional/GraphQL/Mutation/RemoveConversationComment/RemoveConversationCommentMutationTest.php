<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation\RemoveConversationComment;

use App\Entity\ConversationComment;
use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Mutation\RemoveConversationCommentMutation
 * @covers \App\Message\CommandHandler\RemoveConversationCommentHandler
 */
class RemoveConversationCommentMutationTest extends GraphQLTestCase
{
    public function test_remove_comment(): void
    {
        $this->authenticate();
        $lastCommentId = $this->getLastComment()->getId();

        $mutation = <<<'EOF'
            mutation($commentId: ID!) {
              removeConversationComment(commentId: $commentId)
            }
            EOF;

        $this->executeMutation($mutation, ['commentId' => $lastCommentId]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesPattern('{"data":{"removeConversationComment":"OK"}}');
        $this->assertNull($this->getRepository(ConversationComment::class)->find($lastCommentId));
    }

    private function getLastComment(): ConversationComment
    {
        $comment = $this->getRepository(ConversationComment::class)->findOneBy([], ['id' => 'DESC']);
        $this->assertNotNull($comment);

        return $comment;
    }
}
