<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Mutation;

use App\DataFixtures\UserFixture;
use App\Entity\DoctrineUser;
use App\Test\GraphQLTestCase;

/**
 * @covers \App\GraphQL\Mutation\CompleteOnboardingMutation
 * @covers \App\MessageHandler\Command\CompleteOnboardingHandler
 */
class CompleteOnboardingMutationTest extends GraphQLTestCase
{
    public function test_complete_onboarding(): void
    {
        $this->authenticate();

        $mutation = <<<'EOF'
                mutation {
                  completeOnboarding
                }
            EOF;

        $this->executeMutation($mutation);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/CompleteOnboardingSuccess.json');

        $user = $this->getRepository(DoctrineUser::class)->findOneBy(['email' => UserFixture::JOHN]);
        $this->assertNotNull($user);
        $this->assertTrue($user->isOnboardingComplete());
    }
}
