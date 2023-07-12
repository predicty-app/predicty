<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation\AcceptInvitationToAccount;

use App\Entity\AccountInvitation;
use App\Message\Event\InvitationToAccountSent;
use App\Test\GraphQLTestCase;
use Symfony\Component\Mime\RawMessage;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

/**
 * @covers \App\GraphQL\Mutation\InviteToAccount
 * @covers \App\MessageHandler\Command\InviteToAccountHandler
 */
class AcceptInvitationToAccountMutationTest extends GraphQLTestCase
{
    use InteractsWithMessenger;

    public function test_accept_invitation_for_new_user(): void
    {
        $this->authenticate();

        $mutation = <<<'EOF'
            mutation($email: String!) {
              inviteToAccount(email: $email)
            }
            EOF;

        $this->executeMutation($mutation, ['email' => 'james.doe@example.com']);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesPattern('{"data":{"inviteToAccount":"OK"}}');

        /** @var AccountInvitation $invitation */
        $invitation = $this->getRepository(AccountInvitation::class)->findOneBy(['email' => 'james.doe@example.com']);

        $this->assertEquals('01H1PQRVGZ665993NY6MCZ2J6X', $invitation->getAccountId());
        $this->assertEquals('01H1PP4HSADWTJZWZ51ZBD92MG', $invitation->getUserId());
        $this->assertSame('james.doe@example.com', $invitation->getEmail());
    }

    public function test_accept_invitation_for_new_user_sends_confirmation_emails(): void
    {
        $this->authenticate();

        $mutation = <<<'EOF'
            mutation($email: String!) {
              inviteToAccount(email: $email)
            }
            EOF;

        $this->executeMutation($mutation, ['email' => 'james.doe@example.com']);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesPattern('{"data":{"inviteToAccount":"OK"}}');

        $email = $this->getMailerMessage();
        $this->assertInstanceOf(RawMessage::class, $email);
        $this->assertEmailHeaderSame($email, 'subject', 'Predicty Account Invitation');
        $this->assertEmailAddressContains($email, 'to', 'james.doe@example.com');
        $this->assertEmailTextBodyContains($email, 'Use link below to accept an invitation from Account 1');
    }

    public function test_invite_to_account_emits_event(): void
    {
        $this->authenticate();

        $mutation = <<<'EOF'
            mutation($email: String!) {
              inviteToAccount(email: $email)
            }
            EOF;

        $this->executeMutation($mutation, ['email' => 'james.doe@example.com']);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesPattern('{"data":{"inviteToAccount":"OK"}}');

        $this->bus('event.bus')->dispatched()->assertContains(InvitationToAccountSent::class);
    }
}
