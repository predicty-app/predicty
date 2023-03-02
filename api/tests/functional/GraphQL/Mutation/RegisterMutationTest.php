<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation;

use App\Test\GraphQLTestCase;
use Symfony\Component\Mime\RawMessage;

/**
 * @covers \App\GraphQL\Mutation\RegisterMutation
 * @covers \App\MessageHandler\Command\RegisterHandler
 */
class RegisterMutationTest extends GraphQLTestCase
{
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
        $this->assertEmailCount(1);
    }

    public function test_email_is_sent_after_registration(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  register(email: "john.doe2@example.com")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertEmailCount(1);

        $email = $this->getMailerMessage();
        $this->assertInstanceOf(RawMessage::class, $email);
        $this->assertEmailHeaderSame($email, 'subject', 'Predicty Account Login');
        $this->assertEmailAddressContains($email, 'to', 'john.doe2@example.com');
        $this->assertEmailTextBodyContains($email, 'Your passcode is');
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
