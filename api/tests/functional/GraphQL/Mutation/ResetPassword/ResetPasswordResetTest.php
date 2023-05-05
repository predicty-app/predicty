<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation\ResetPassword;

use App\DataFixtures\UserFixture;
use App\Entity\DoctrineUser;
use App\Message\Event\UserResetPassword;
use App\Service\Security\PasswordReset\PasswordResetService;
use App\Test\GraphQLTestCase;
use Symfony\Component\Mime\RawMessage;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

/**
 * @covers \App\GraphQL\Mutation\ResetPasswordMutation
 * @covers \App\MessageHandler\Command\ResetPasswordHandler
 */
class ResetPasswordResetTest extends GraphQLTestCase
{
    use InteractsWithMessenger;

    public function test_reset_password(): void
    {
        $this->loadFixtures([UserFixture::class]);
        $user = $this->getReference(UserFixture::JOHN, DoctrineUser::class);
        $token = static::getContainer()->get(PasswordResetService::class)->createToken($user);

        $mutation = <<<'EOF'
                mutation($token: String!) {
                  resetPassword(token: $token, password: "newPassword")
                }
            EOF;

        $this->executeMutation($mutation, ['token' => $token]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/ResetPasswordSuccess.json');
    }

    public function test_request_password_reset_emits_event(): void
    {
        $this->loadFixtures([UserFixture::class]);
        $user = $this->getReference(UserFixture::JOHN, DoctrineUser::class);
        $token = static::getContainer()->get(PasswordResetService::class)->createToken($user);

        $mutation = <<<'EOF'
                mutation($token: String!) {
                  resetPassword(token: $token, password: "newPassword")
                }
            EOF;

        $this->executeMutation($mutation, ['token' => $token]);
        $this->assertResponseIsSuccessful();
        $this->bus('event.bus')->dispatched()->assertContains(UserResetPassword::class);
    }

    public function test_request_password_reset_sends_notification_email(): void
    {
        $this->loadFixtures([UserFixture::class]);
        $user = $this->getReference(UserFixture::JOHN, DoctrineUser::class);
        $token = static::getContainer()->get(PasswordResetService::class)->createToken($user);

        $mutation = <<<'EOF'
                mutation($token: String!) {
                  resetPassword(token: $token, password: "newPassword")
                }
            EOF;

        $this->executeMutation($mutation, ['token' => $token]);
        $this->assertResponseIsSuccessful();

        $email = $this->getMailerMessage();
        $this->assertInstanceOf(RawMessage::class, $email);
        $this->assertEmailHeaderSame($email, 'subject', 'Predicty Account Password Reset');
        $this->assertEmailAddressContains($email, 'to', 'john.doe@example.com');
        $this->assertEmailTextBodyContains($email, 'Your account password was reset successfully');
    }

    public function test_request_password_fails_silently_when_invalid_token_is_provided(): void
    {
        $mutation = <<<'EOF'
                mutation($token: String!) {
                  resetPassword(token: $token, password: "newPassword")
                }
            EOF;

        $this->executeMutation($mutation, ['token' => 'asdf']);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/ResetPasswordFail1.json');
        $this->assertEmailCount(0);
    }

    public function test_request_password_token_works_only_once(): void
    {
        $this->loadFixtures([UserFixture::class]);
        $user = $this->getReference(UserFixture::JOHN, DoctrineUser::class);
        $token = static::getContainer()->get(PasswordResetService::class)->createToken($user);

        $mutation = <<<'EOF'
                mutation($token: String!) {
                  resetPassword(token: $token, password: "newPassword")
                }
            EOF;

        $this->executeMutation($mutation, ['token' => $token]);
        $this->assertResponseIsSuccessful();

        $this->executeMutation($mutation, ['token' => $token]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/ResetPasswordFail1.json');
    }

    public function test_request_password_fails_when_empty_password_is_provided(): void
    {
        $mutation = <<<'EOF'
                mutation($token: String!) {
                  resetPassword(token: $token, password: "")
                }
            EOF;

        $this->executeMutation($mutation, ['token' => 'asdf']);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/ResetPasswordFail2.json');
        $this->assertEmailCount(0);
    }
}
