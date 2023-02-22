<?php

declare(strict_types=1);

namespace App\GraphQL;

use App\GraphQL\Type\UserType;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Type\Definition\Type;
use Symfony\Component\DependencyInjection\ServiceLocator;

/**
 * @method UserType user()
 */
class TypeResolver
{
    private static array $guessingNamespaces = [
        'GraphQL\Type\Definition',
        'App\GraphQL\Type',
        'App\GraphQL\Mutation',
        'App\GraphQL\Query',
    ];

    public function __construct(private readonly ServiceLocator $serviceLocator)
    {
    }

    public function __invoke(string $typeName): Type
    {
        return $this->get($typeName);
    }

    public function __call(string $name, array $arguments = []): Type
    {
        return $this->get($name);
    }

    public function boolean(): ScalarType
    {
        return Type::boolean();
    }

    public function float(): ScalarType
    {
        return Type::float();
    }

    public function id(): ScalarType
    {
        return Type::id();
    }

    public function int(): ScalarType
    {
        return Type::int();
    }

    public function string(): ScalarType
    {
        return Type::string();
    }

    /**
     * @return ListOfType<Type>
     */
    public function listOf(callable|Type $type): ListOfType
    {
        return new ListOfType($type);
    }

    public function nonNull(Type|callable $type): NonNull
    {
        /** @phpstan-ignore-next-line */
        return new NonNull($type);
    }

    public function get(string $typeName): Type
    {
        return $this->serviceLocator->get($this->guessType($typeName));
    }

    private function guessType(string $typeName): string
    {
        if (is_a($typeName, Type::class, true)) {
            return $typeName;
        }

        $typeName = ucfirst($typeName);

        foreach (self::$guessingNamespaces as $namespace) {
            $guessedClassName = $namespace.'\\'.$typeName.'Type';
            if (is_a($guessedClassName, Type::class, true)) {
                return $guessedClassName;
            }
        }

        throw new \RuntimeException(sprintf('Unable to guess type for: "%s"', $typeName));
    }
}
