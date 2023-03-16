<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Mutation\CompleteOnboardingMutation
 * @covers \App\Message\CommandHandler\CompleteOnboardingHandler
 */
class CompleteOnboardingMutationTest extends GraphQLTestCase
{
    public function test_complete_onboarding(): void
    {
        $this->loadFixtures([UserFixtures::class]);
        $user = $this->getReference(UserFixtures::JOHN, User::class);
        $this->authenticate($user);

        $mutation = <<<'EOF'
                mutation {
                  completeOnboarding
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/CompleteOnboardingSuccess.json');
        $this->assertTrue($user->isOnboardingComplete());
    }
}
