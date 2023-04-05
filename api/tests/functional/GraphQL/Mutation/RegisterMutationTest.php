<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation;

use App\Entity\User;
use App\Test\GraphQLTestCase;
use Symfony\Component\Mime\RawMessage;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

/**
 * @covers \App\GraphQL\Mutation\RegisterMutation
 * @covers \App\Message\CommandHandler\RegisterHandler
 */
class RegisterMutationTest extends GraphQLTestCase
{
    use InteractsWithMessenger;

    public function test_register(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  register(email: "john.doe2@example.com")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/RegisterMutationSuccess.json');

        $user = $this->getRepository(User::class)->findOneBy(['email' => 'john.doe2@example.com']);
        $this->assertInstanceOf(User::class, $user);
    }

    public function test_register_sends_two_emails_after_registration(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  register(email: "john.doe2@example.com")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertEmailCount(2);
    }

    public function test_email_with_passcode_is_sent_after_registration(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  register(email: "john.doe2@example.com")
                }
            EOF;

        $this->executeMutation($mutation);

        $email = $this->getMailerMessage(1);
        $this->assertInstanceOf(RawMessage::class, $email);
        $this->assertEmailHeaderSame($email, 'subject', 'Predicty Account Login');
        $this->assertEmailAddressContains($email, 'to', 'john.doe2@example.com');
        $this->assertEmailTextBodyContains($email, 'Your passcode is');
    }

    public function test_email_with_generated_password_is_sent_after_registration(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  register(email: "john.doe2@example.com")
                }
            EOF;

        $this->executeMutation($mutation);

        $email = $this->getMailerMessage(0);
        $this->assertInstanceOf(RawMessage::class, $email);
        $this->assertEmailHeaderSame($email, 'subject', 'Predicty Account Registration');
        $this->assertEmailAddressContains($email, 'to', 'john.doe2@example.com');
        $this->assertEmailTextBodyContains($email, 'We generated a new password for you');
    }

    public function test_register_sends_passcode_again_if_email_is_already_used(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  register(email: "john.doe@example.com")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/RegisterMutationSuccess.json');
        $this->assertEmailCount(1);

        $email = $this->getMailerMessage();
        $this->assertInstanceOf(RawMessage::class, $email);
        $this->assertEmailHeaderSame($email, 'subject', 'Predicty Account Login');
        $this->assertEmailAddressContains($email, 'to', 'john.doe@example.com');
        $this->assertEmailTextBodyContains($email, 'Your passcode is');
    }

    public function test_register_returns_error_if_invalid_email_is_used(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  register(email: "asdf")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/RegisterMutationFailedInvalidEmail.json');
    }

    public function test_register_returns_error_if_empty_email_is_used(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  register(email: "")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/RegisterMutationFailedEmptyEmail.json');
    }
}
