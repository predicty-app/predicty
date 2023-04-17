<?php

declare(strict_types=1);

namespace App\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class GraphQLTestCase extends WebTestCase
{
    use AuthenticatorTrait;
    use EntityManagerTrait;
    use FixturesTrait;
    use KernelBrowserTrait;
    use PHPMatcherAssertionsTrait;

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
        static::executeQuery($query, $variables, $operationName);
    }
}
