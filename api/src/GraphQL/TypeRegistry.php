<?php

declare(strict_types=1);

namespace App\GraphQL;

use App\GraphQL\Type\AdCollectionType;
use App\GraphQL\Type\AdSetType;
use App\GraphQL\Type\AdStatsType;
use App\GraphQL\Type\AdType;
use App\GraphQL\Type\ApiImportType;
use App\GraphQL\Type\CampaignType;
use App\GraphQL\Type\DailyRevenueType;
use App\GraphQL\Type\DashboardType;
use App\GraphQL\Type\DataProviderIdType;
use App\GraphQL\Type\DataProviderType;
use App\GraphQL\Type\FileImportType;
use App\GraphQL\Type\FileImportTypeType;
use App\GraphQL\Type\ImportResultType;
use App\GraphQL\Type\ImportStatusType;
use App\GraphQL\Type\ImportType;
use App\GraphQL\Type\MoneyType;
use App\GraphQL\Type\UserType;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Type\Definition\Type;
use GraphQL\Upload\UploadType;
use MLL\GraphQLScalars\Date;
use MLL\GraphQLScalars\DateTime;
use RuntimeException;
use Symfony\Component\DependencyInjection\ServiceLocator;

/**
 * @method UserType           user()
 * @method DashboardType      dashboard()
 * @method CampaignType       campaign()
 * @method AdSetType          adSet()
 * @method AdType             ad()
 * @method AdStatsType        adStats()
 * @method MoneyType          money()
 * @method AdCollectionType   adCollection()
 * @method FileImportTypeType fileImportType()
 * @method DataProviderIdType dataProviderId()
 * @method DataProviderType   dataProvider()
 * @method Date               date()
 * @method DateTime           dateTime()
 * @method DailyRevenueType   dailyRevenue()
 * @method ImportStatusType   importStatus()
 * @method ImportType         import()
 * @method ApiImportType      apiImport()
 * @method FileImportType     fileImport()
 * @method ImportResultType   importResult()
 */
class TypeRegistry
{
    private static array $guessingNamespaces = [
        'GraphQL\Type\Definition\%sType',
        'MLL\GraphQLScalars\%s',
        'App\GraphQL\Type\%sType',
        'GraphQL\Upload\%sType',
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
        assert($arguments === [], 'No arguments are allowed');

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

    public function nonNullId(): NonNull
    {
        return new NonNull($this->id());
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

    public function nonNullString(): NonNull
    {
        return new NonNull($this->string());
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
            $guessedClassName = sprintf($namespace, $typeName);
            if (is_a($guessedClassName, Type::class, true)) {
                return $guessedClassName;
            }
        }

        throw new RuntimeException(sprintf('Unable to guess type for: "%s"', $typeName));
    }
}
