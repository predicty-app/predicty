<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation\ChangePassword;

use App\Message\Event\UserChangedPassword;
use App\Test\GraphQLTestCase;
use Symfony\Component\Mime\RawMessage;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

/**
 * @covers \App\GraphQL\Mutation\ChangePasswordMutation
 * @covers \App\Message\CommandHandler\ChangePasswordHandler
 */
class ChangePasswordTest extends GraphQLTestCase
{
    use InteractsWithMessenger;

    public function test_change_password(): void
    {
        $this->authenticate();

        $mutation = <<<'EOF'
                mutation {
                  changePassword(oldPassword: "123456", newPassword: "new_password")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/ChangePasswordSuccess.json');
        $this->bus('event.bus')->dispatched()->assertContains(UserChangedPassword::class);
    }

    public function test_change_password_sends_email_notification(): void
    {
        $this->authenticate();

        $mutation = <<<'EOF'
                mutation {
                  changePassword(oldPassword: "123456", newPassword: "new_password")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertEmailCount(1);
        $email = $this->getMailerMessage();
        $this->assertInstanceOf(RawMessage::class, $email);
        $this->assertEmailHeaderSame($email, 'subject', 'Predicty Account Password Changed');
        $this->assertEmailAddressContains($email, 'to', 'john.doe@example.com');
        $this->assertEmailTextBodyContains($email, 'Your account password was changed successfully');
    }

    public function test_change_password_fails_when_both_passwords_are_equal(): void
    {
        $this->authenticate();

        $mutation = <<<'EOF'
                mutation {
                  changePassword(oldPassword: "123456", newPassword: "123456")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/ChangePasswordFailure1.json');
        $this->assertEmailCount(0);
    }

    public function test_change_password_fails_if_new_password_is_empty(): void
    {
        $this->authenticate();

        $mutation = <<<'EOF'
                mutation {
                  changePassword(oldPassword: "123456", newPassword: "")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/ChangePasswordFailure2.json');
        $this->assertEmailCount(0);
    }

    public function test_change_password_fails_if_old_password_does_not_match(): void
    {
        $this->authenticate();

        $mutation = <<<'EOF'
                mutation {
                  changePassword(oldPassword: "asdf", newPassword: "123456")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/ChangePasswordFailure3.json');
        $this->assertEmailCount(0);
    }

    public function test_user_can_login_using_new_password(): void
    {
        $this->authenticate();

        $mutation = <<<'EOF'
                mutation {
                  changePassword(oldPassword: "123456", newPassword: "new_password")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();

        $mutation = <<<'EOF'
                mutation {
                  logout
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();

        $mutation = <<<'EOF'
                mutation {
                  loginWithPassword(username: "john.doe@example.com", password: "new_password") {
                    email
                  }
                }
            EOF;
        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/LoginAfterPasswordChange.json');
    }
}
