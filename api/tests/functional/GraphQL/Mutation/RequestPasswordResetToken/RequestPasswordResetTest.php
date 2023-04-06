<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation\RequestPasswordResetToken;

use App\Message\Event\UserRequestedPasswordResetToken;
use App\Test\GraphQLTestCase;
use Symfony\Component\Mime\RawMessage;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

/**
 * @covers \App\GraphQL\Mutation\RequestPasswordResetTokenMutation
 * @covers \App\Message\CommandHandler\RequestPasswordResetTokenHandler
 */
class RequestPasswordResetTest extends GraphQLTestCase
{
    use InteractsWithMessenger;

    public function test_request_password_reset(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  requestPasswordResetToken(username: "john.doe@example.com")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/RequestPasswordResetSuccess.json');
    }

    public function test_request_password_reset_emits_event(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  requestPasswordResetToken(username: "john.doe@example.com")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->bus('event.bus')->dispatched()->assertContains(UserRequestedPasswordResetToken::class);
    }

    public function test_request_password_reset_sends_email_with_link(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  requestPasswordResetToken(username: "john.doe@example.com")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();

        $email = $this->getMailerMessage();
        $this->assertInstanceOf(RawMessage::class, $email);
        $this->assertEmailHeaderSame($email, 'subject', 'Predicty Account Password Reset Requested');
        $this->assertEmailAddressContains($email, 'to', 'john.doe@example.com');
        $this->assertEmailTextBodyContains($email, 'Use link below to reset your password');
    }

    public function test_request_password_fails_when_empty_username_is_provided(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  requestPasswordResetToken(username: "")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/RequestPasswordResetFail1.json');
    }

    public function test_request_password_fails_when_invalid_username_is_provided(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  requestPasswordResetToken(username: "asdf")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/RequestPasswordResetFail2.json');
    }
}
