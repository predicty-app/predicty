<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation\Register;

use App\Entity\Account;
use App\Entity\DoctrineUser;
use App\Message\Event\AccountCreated;
use App\Message\Event\UserRegistered;
use App\Test\GraphQLTestCase;
use Symfony\Component\Mime\RawMessage;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

/**
 * @covers \App\GraphQL\Mutation\RegisterMutation
 * @covers \App\MessageHandler\Command\RegisterHandler
 * @covers \App\MessageHandler\Command\CreateAccountHandler
 * @covers \App\MessageHandler\Command\RequestPasscodeHandler
 */
class RegisterMutationTest extends GraphQLTestCase
{
    use InteractsWithMessenger;

    public function test_register(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  register(email: "john.doe2@example.com", acceptedTermsOfServiceVersion: 1, hasAgreedToNewsletter: true)
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/RegisterMutationSuccess.json');

        $user = $this->getRepository(DoctrineUser::class)->findOneBy(['email' => 'john.doe2@example.com']);
        $this->assertInstanceOf(DoctrineUser::class, $user);
        $this->assertSame(1, $user->getAcceptedTermsOfServiceVersion());
        $this->assertTrue($user->hasAgreedToNewsletter());
    }

    public function test_register_creates_a_new_account_and_adds_account_owner_role(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  register(email: "john.doe2@example.com", acceptedTermsOfServiceVersion: 1, hasAgreedToNewsletter: true)
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();

        $user = $this->getRepository(DoctrineUser::class)->findOneBy(['email' => 'john.doe2@example.com']);
        $this->assertNotNull($user);

        $account = $this->getRepository(Account::class)->findOneBy(['userId' => $user->getId()]);
        $this->assertNotNull($account);

        $this->assertSame(['ROLE_USER'], $user->getRoles());
        $this->assertContains('ROLE_ACCOUNT_OWNER', $user->getRolesForAccount($account));

        $this->bus('event.bus')->dispatched()->assertContains(UserRegistered::class);
        $this->bus('event.bus')->dispatched()->assertContains(AccountCreated::class);
    }

    public function test_register_sends_two_emails_after_registration(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  register(email: "john.doe2@example.com", acceptedTermsOfServiceVersion: 1, hasAgreedToNewsletter: true)
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertEmailCount(2);
    }

    public function test_email_with_passcode_is_sent_after_registration(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  register(email: "john.doe2@example.com", acceptedTermsOfServiceVersion: 1, hasAgreedToNewsletter: true)
                }
            EOF;

        $this->executeMutation($mutation);

        $email = $this->getMailerMessage(0);
        $this->assertInstanceOf(RawMessage::class, $email);
        $this->assertEmailHeaderSame($email, 'subject', 'Predicty Account Login');
        $this->assertEmailAddressContains($email, 'to', 'john.doe2@example.com');
        $this->assertEmailTextBodyContains($email, 'Your passcode is');
    }

    public function test_email_with_generated_password_is_sent_after_registration(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  register(email: "john.doe2@example.com", acceptedTermsOfServiceVersion: 1, hasAgreedToNewsletter: true)
                }
            EOF;

        $this->executeMutation($mutation);

        $email = $this->getMailerMessage(1);
        $this->assertInstanceOf(RawMessage::class, $email);
        $this->assertEmailHeaderSame($email, 'subject', 'Predicty Account Registration');
        $this->assertEmailAddressContains($email, 'to', 'john.doe2@example.com');
        $this->assertEmailTextBodyContains($email, 'We generated a new password for you');
    }

    public function test_register_sends_passcode_again_if_email_is_already_used(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  register(email: "john.doe@example.com", acceptedTermsOfServiceVersion: 1, hasAgreedToNewsletter: true)
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/RegisterMutationSuccess.json');
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
                  register(email: "asdf", acceptedTermsOfServiceVersion: 1, hasAgreedToNewsletter: true)
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/RegisterMutationFailedInvalidEmail.json');
    }

    public function test_register_returns_error_if_empty_email_is_used(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  register(email: "", acceptedTermsOfServiceVersion: 1, hasAgreedToNewsletter: true)
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/RegisterMutationFailedEmptyEmail.json');
    }

    public function test_register_returns_error_if_user_did_not_accept_the_terms(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  register(email: "john.doe2@example.com", acceptedTermsOfServiceVersion: null, hasAgreedToNewsletter: true)
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/RegisterMutationFailedNotAcceptedTerms.json');
    }

    public function test_register_returns_error_if_client_does_not_provide_terms_version(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  register(email: "john.doe2@example.com")
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/RegisterMutationFailedNotAcceptedTerms.json');
    }

    public function test_register_newsletter_is_by_opted_out(): void
    {
        $mutation = <<<'EOF'
                mutation {
                  register(email: "john.doe2@example.com", acceptedTermsOfServiceVersion: 1)
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/RegisterMutationDefaultNewsletterSetting.json');

        $user = $this->getRepository(DoctrineUser::class)->findOneBy(['email' => 'john.doe2@example.com']);
        $this->assertNotNull($user);

        $this->assertFalse($user->hasAgreedToNewsletter());
    }
}
