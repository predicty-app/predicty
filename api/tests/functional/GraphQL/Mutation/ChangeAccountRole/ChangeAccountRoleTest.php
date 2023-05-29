<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation\ChangeAccountRole;

use App\DataFixtures\UserFixture;
use App\Entity\AccountAwareUser;
use App\Entity\DoctrineUser;
use App\Entity\UserWithId;
use App\Message\Event\UserRoleChanged;
use App\Test\GraphQLTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

/**
 * @covers \App\GraphQL\Mutation\ChangeAccountRoleMutation
 * @covers \App\MessageHandler\Command\ChangeAccountRoleHandler
 */
class ChangeAccountRoleTest extends GraphQLTestCase
{
    use InteractsWithMessenger;

    public function test_change_account_role(): void
    {
        $this->authenticate();

        $user = $this->getUser();
        assert($user instanceof AccountAwareUser);

        $jane = $this->getRepository(DoctrineUser::class)->findOneBy(['email' => UserFixture::JANE]);
        assert($jane !== null);

        $mutation = <<<'EOF'
                mutation($userId: ID!, $role: String!) {
                  changeAccountRole(userId: $userId, role: $role)
                }
            EOF;

        $this->executeMutation($mutation, [
            'userId' => $jane->getId(),
            'role' => 'ROLE_ACCOUNT_OWNER',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/ChangeAccountRoleSuccess.json');
        $this->bus('event.bus')->dispatched()->assertContains(UserRoleChanged::class);

        $this->assertSame(['ROLE_ACCOUNT_OWNER'], $jane->getRolesForAccount($user->getAccountId()));
    }

    public function test_change_account_role_for_yourself_is_not_allowed(): void
    {
        $this->authenticate();

        $user = $this->getUser();
        assert($user instanceof AccountAwareUser);
        assert($user instanceof UserWithId);

        $mutation = <<<'EOF'
                mutation($userId: ID!, $role: String!) {
                  changeAccountRole(userId: $userId, role: $role)
                }
            EOF;

        $this->executeMutation($mutation, [
            'userId' => $user->getId(),
            'role' => 'ROLE_ACCOUNT_OWNER',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/ChangeAccountRoleFailure1.json');
        $this->bus('event.bus')->dispatched()->assertNotContains(UserRoleChanged::class);
    }

    public function test_change_account_role_is_allowed_only_for_account_owners(): void
    {
        $this->authenticate(UserFixture::JANE);

        $user = $this->getUser();
        assert($user instanceof AccountAwareUser);

        $john = $this->getRepository(DoctrineUser::class)->findOneBy(['email' => UserFixture::JOHN]);
        assert($john !== null);

        $mutation = <<<'EOF'
                mutation($userId: ID!, $role: String!) {
                  changeAccountRole(userId: $userId, role: $role)
                }
            EOF;

        $this->executeMutation($mutation, [
            'userId' => $john->getId(),
            'role' => 'ROLE_ACCOUNT_OWNER',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/ChangeAccountRoleFailure2.json');
        $this->bus('event.bus')->dispatched()->assertNotContains(UserRoleChanged::class);
    }
}
