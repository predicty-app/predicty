<?php

declare(strict_types=1);

namespace App\Test;

use App\Entity\User;
use App\Repository\UserRepository;
use Coduo\PHPMatcher\Backtrace;
use Coduo\PHPMatcher\PHPUnit\PHPMatcherConstraint;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class GraphQLTestCase extends WebTestCase
{
    use FixturesTrait;

    private static ?KernelBrowser $client = null;
    private static ?Backtrace $backtrace = null;

    protected function tearDown(): void
    {
        self::$client = null;
        parent::tearDown();
    }

    public static function authenticate(User|string $user = null): void
    {
        if ($user === null) {
            $user = 'john.doe@example.com';
        }

        if ($user instanceof User) {
            $user = $user->getEmail();
        }

        $client = static::getClient();
        $users = static::getContainer()->get(UserRepository::class);
        $user = $users->findByUsername($user);
        assert($user instanceof UserInterface);
        $client->loginUser($user);
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $className
     *
     * @return EntityRepository<T>
     */
    public function getRepository(string $className): EntityRepository
    {
        return $this->getEntityManager()->getRepository($className);
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return static::getClient()->getContainer()->get(EntityManagerInterface::class);
    }

    public static function createClient(array $options = [], array $server = []): KernelBrowser
    {
        self::$client = parent::createClient($options, $server);

        return self::$client;
    }

    public static function executeQuery(string $query, array $variables = [], string $operationName = null): void
    {
        $parameters = [
            'operationName' => $operationName,
            'query' => $query,
            'variables' => $variables,
        ];

        static::getClient()->jsonRequest(
            method: 'POST',
            uri: '/graphql',
            parameters: $parameters
        );
    }

    public static function executeMutation(string $query, array $variables = [], string $operationName = null): void
    {
        self::executeQuery($query, $variables, $operationName);
    }

    public static function assertResponseMatchesJsonFile(string $filename, string $message = ''): void
    {
        $responseContent = (string) static::getClient()->getResponse()->getContent();

        $dirname = dirname($filename);

        if (!is_dir($dirname)) {
            mkdir($dirname, 0o777, true);
        }

        if (!file_exists($filename)) {
            file_put_contents($filename, $responseContent);
            static::fail(sprintf('Response saved to: "%s", re-run this test to use it.', $filename));
        }

        $pattern = (string) file_get_contents($filename);
        static::assertMatchesPattern($pattern, $responseContent, $message);
    }

    protected static function getClient(): KernelBrowser
    {
        if (self::$client === null) {
            static::createClient();
        }
        assert(self::$client !== null);

        return self::$client;
    }

    protected static function setBacktrace(Backtrace $backtrace): void
    {
        self::$backtrace = $backtrace;
    }

    protected static function assertMatchesPattern(string $pattern, string $value, string $message = ''): void
    {
        TestCase::assertThat($value, self::matchesPattern($pattern, self::$backtrace), $message);
    }

    protected static function matchesPattern(string $pattern, ?Backtrace $backtrace = null): PHPMatcherConstraint
    {
        return new PHPMatcherConstraint($pattern, $backtrace);
    }
}
