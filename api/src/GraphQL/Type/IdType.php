<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use GraphQL\Error\Error;
use GraphQL\Error\SerializationError;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Language\Printer;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;
use Symfony\Component\Uid\Ulid;

class IdType extends ScalarType
{
    public string $name = 'ID';

    public ?string $description
        = 'The `ID` scalar type represents a unique identifier (ULID). The ID type appears in a JSON
response as a String; however, it is not intended to be human-readable.';

    /**
     * @param mixed $value
     *
     * @throws SerializationError
     */
    public function serialize($value): string
    {
        if (!$value instanceof Ulid) {
            $notID = Utils::printSafe($value);

            throw new SerializationError("ID cannot represent a non-ulid value: {$notID}");
        }

        return (string) $value;
    }

    /**
     * @param mixed $value
     *
     * @throws Error
     */
    public function parseValue($value): Ulid
    {
        $value = (string) $value;
        if (Ulid::isValid($value)) {
            return Ulid::fromString($value);
        }

        $notID = Utils::printSafeJson($value);

        throw new Error(message: "ID cannot represent a non-ulid value: {$notID}");
    }

    /**
     * @throws Error
     */
    public function parseLiteral(Node $valueNode, ?array $variables = null): Ulid
    {
        if ($valueNode instanceof StringValueNode) {
            if (Ulid::isValid($valueNode->value)) {
                return Ulid::fromString($valueNode->value);
            }
        }

        $notID = Printer::doPrint($valueNode);

        throw new Error("ID cannot represent a non-ulid value: {$notID}", $valueNode);
    }
}
