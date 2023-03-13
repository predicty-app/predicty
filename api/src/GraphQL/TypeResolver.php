<?php

declare(strict_types=1);

namespace App\GraphQL;

use App\GraphQL\Type\AdCollectionType;
use App\GraphQL\Type\AdSetType;
use App\GraphQL\Type\AdStatsType;
use App\GraphQL\Type\AdType;
use App\GraphQL\Type\CampaignType;
use App\GraphQL\Type\DashboardType;
use App\GraphQL\Type\MoneyType;
use App\GraphQL\Type\UserType;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Type\Definition\Type;
use GraphQL\Upload\UploadType;
use RuntimeException;
use Symfony\Component\DependencyInjection\ServiceLocator;

/**
 * @method UserType         user()
 * @method DashboardType    dashboard()
 * @method CampaignType     campaign()
 * @method AdSetType        adSet()
 * @method AdType           ad()
 * @method AdStatsType      adStats()
 * @method MoneyType        money()
 * @method AdCollectionType adCollection()
 */
class TypeResolver
{
    private static array $guessingNamespaces = [
        'GraphQL\Type\Definition',
        'App\GraphQL\Type',
        'App\GraphQL\Mutation',
        'App\GraphQL\Query',
        'GraphQL\Upload',
    ];

    public function __construct(private readonly ServiceLocator $serviceLocator)
    {
    }

    /**
     * @template T of Type
     *
     * @param class-string<T> $typeName
     *
     * @return T
     */
    public function __call(string $typeName, array $arguments = []): Type
    {
        return $this->get($typeName);
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

    public function upload(): UploadType
    {
        return $this->get(UploadType::class);
    }

    /**
     * @template T of Type
     *
     * @param class-string<T> $typeName
     *
     * @return T
     */
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

        throw new RuntimeException(sprintf('Unable to guess type for: "%s"', $typeName));
    }
}
