<?php

declare(strict_types=1);

namespace App\Tests\Functional\GraphQL\Query;

use App\Repository\UserRepository;
use App\Test\GraphQLTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @covers \App\GraphQL\Query\DashboardQuery
 */
class DashboardQueryTest extends GraphQLTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function test_get_dashboard_details(): void
    {
        $users = static::getContainer()->get(UserRepository::class);
        $user = $users->findByUsername('john.doe@example.com');
        assert($user instanceof UserInterface);

        $this->client->loginUser($user);

        $query = <<<'EOF'
            query {
              dashboard {
                name,
              }
            }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/DashboardDetails.json');
    }

    public function test_get_dashboard_collections(): void
    {
        $users = static::getContainer()->get(UserRepository::class);
        $user = $users->findByUsername('john.doe@example.com');
        assert($user instanceof UserInterface);

        $this->client->loginUser($user);

        $query = <<<'EOF'
            query {
              dashboard {
                collections {
                  id,
                  name,
                }
              }
            }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/DashboardCollections.json');
    }

    public function test_get_dashboard_campaigns(): void
    {
        $users = static::getContainer()->get(UserRepository::class);
        $user = $users->findByUsername('john.doe@example.com');
        assert($user instanceof UserInterface);

        $this->client->loginUser($user);

        $query = <<<'EOF'
            query {
              dashboard {
                 campaigns{
                  id,
                  name,
                }
              }
            }
            EOF;

        $this->executeQuery($query);
        $this->assertResponseIsSuccessful();
        $this->assertResponseMatchesJsonFile(__DIR__.'/response/DashboardCampaigns.json');
    }
}
