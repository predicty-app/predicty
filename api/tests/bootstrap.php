<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0);
}

function prepareTestDatabase(): void
{
    passthru('php bin/console doctrine:database:drop --if-exists --force --env=test');
    passthru('php bin/console doctrine:database:create --env=test');
    passthru('php bin/console doctrine:schema:create --quiet --env=test');
}
