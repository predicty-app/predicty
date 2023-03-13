<?php

declare(strict_types=1);

namespace App\Extension\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

/**
 * Hotfix.
 *
 * @see https://github.com/doctrine/dbal/issues/1110#issuecomment-1263653593
 */
class MigrationEventSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            'postGenerateSchema',
        ];
    }

    public function postGenerateSchema(GenerateSchemaEventArgs $Args): void
    {
        // This listener helps to prevent generating 'down' migrations trying to remove a 'public' schema for ever
        // however it confuses the doctrine:schema:create command which tries to recreate a 'public' table.
        // The workaround is to run doctrine:schema:create command with the following environment variable
        // set to a non-empty value, then unsetting that variable forever.
        if (empty(getenv('NO_PUBLIC_SCHEMA_CREATE_PLEASE'))) {
            $Schema = $Args->getSchema();

            if (!$Schema->hasNamespace('public')) {
                $Schema->createNamespace('public');
            }
        }
    }
}
