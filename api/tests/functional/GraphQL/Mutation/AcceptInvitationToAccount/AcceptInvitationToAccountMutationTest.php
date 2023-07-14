<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation\AcceptInvitationToAccount;

use App\DataFixtures\AccountFixture;
use App\DataFixtures\UserFixture;
use App\Entity\AccountInvitation;
use App\Entity\DoctrineUser;
use App\Entity\User;
use App\Service\Clock\Clock;
use App\Test\GraphQLTestCase;
use Symfony\Component\Mime\RawMessage;
use Symfony\Component\Uid\Ulid;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

/**
 * @covers \App\GraphQL\Mutation\InviteToAccount
 * @covers \App\MessageHandler\Command\InviteToAccountHandler
 */
class AcceptInvitationToAccountMutationTest extends GraphQLTestCase
{
    use InteractsWithMessenger;

    public function test_accept_invitation_for_new_user_creates_new_user_account(): void
    {
        $invitation = $this->prepareInvitation();

        $mutation = <<<'EOF'
            mutation($invitationId: ID!) {
              acceptInvitationToAccount(invitationId: $invitationId, acceptedTermsOfServiceVersion: 1, hasAgreedToNewsletter: true)
            }
            EOF;

        $this->executeMutation($mutation, ['invitationId' => $invitation->getId()]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesPattern('{"data":{"acceptInvitationToAccount":"OK"}}');

        $newlyCreatedUser = $this->getRepository(DoctrineUser::class)->findOneBy(['email' => 'jason.doe@example.com']);
        $this->assertInstanceOf(User::class, $newlyCreatedUser);
        $this->assertTrue($newlyCreatedUser->isMemberOf(Ulid::fromString(AccountFixture::ACCOUNT_1)));
        $this->assertSame(['ROLE_ACCOUNT_MEMBER'], $newlyCreatedUser->getRolesForAccount(Ulid::fromString(AccountFixture::ACCOUNT_1)));
        $this->assertTrue($newlyCreatedUser->hasAgreedToNewsletter());
        $this->assertTrue($newlyCreatedUser->hasAgreedToTerms(1));
        $this->assertSame('jason.doe@example.com', $newlyCreatedUser->getEmail());
        $this->assertTrue($newlyCreatedUser->hasVerifiedEmail());
    }

    public function test_accept_invitation_sends_confirmation_emails(): void
    {
        $invitation = $this->prepareInvitation();

        $mutation = <<<'EOF'
            mutation($invitationId: ID!) {
              acceptInvitationToAccount(invitationId: $invitationId, acceptedTermsOfServiceVersion: 1, hasAgreedToNewsletter: true)
            }
            EOF;

        $this->executeMutation($mutation, ['invitationId' => $invitation->getId()]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesPattern('{"data":{"acceptInvitationToAccount":"OK"}}');

        $email1 = $this->getMailerMessage(0);
        $this->assertInstanceOf(RawMessage::class, $email1);
        $this->assertEmailHeaderSame($email1, 'subject', 'Predicty Account Login');
        $this->assertEmailAddressContains($email1, 'to', 'jason.doe@example.com');

        $email2 = $this->getMailerMessage(1);
        $this->assertInstanceOf(RawMessage::class, $email2);
        $this->assertEmailHeaderSame($email2, 'subject', 'Predicty Account Registration');

        $email3 = $this->getMailerMessage(2);
        $this->assertInstanceOf(RawMessage::class, $email3);
        $this->assertEmailHeaderSame($email3, 'subject', 'Predicty Account - Welcome to Account 1');
        $this->assertEmailAddressContains($email3, 'to', 'jason.doe@example.com');

        $email4 = $this->getMailerMessage(3);
        $this->assertInstanceOf(RawMessage::class, $email4);
        $this->assertEmailHeaderSame($email4, 'subject', 'Predicty Account - New member');
        $this->assertEmailAddressContains($email4, 'to', 'john.doe@example.com');
    }

    private function prepareInvitation(): AccountInvitation
    {
        $validTo = Clock::now()->modify('+1 day');

        $invitation = new AccountInvitation(
            id: new Ulid(),
            userId: new Ulid(UserFixture::JOHN_ID),
            accountId: new Ulid(AccountFixture::ACCOUNT_1),
            email: 'jason.doe@example.com',
            validTo: $validTo
        );

        $this->getEntityManager()->persist($invitation);
        $this->getEntityManager()->flush();

        return $invitation;
    }
}
