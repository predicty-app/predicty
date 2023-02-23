<?php

declare(strict_types=1);

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

$env = new Dotenv();
$env->bootEnv(dirname(__DIR__).'/.env');
$env->overload(dirname(__DIR__).'/.env.test');

if ($_SERVER['APP_DEBUG']) {
    umask(0);
}

function bootstrap(): void
{
    $kernel = new Kernel('test', true);
    $kernel->boot();

    $application = new Application($kernel);
    $application->setAutoExit(false);

    $application->run(new ArrayInput([
        'command' => 'doctrine:database:drop',
        '--if-exists' => '1',
        '--force' => '1',
    ]));

    $application->run(new ArrayInput([
        'command' => 'doctrine:database:create',
        '--if-not-exists' => true,
    ]));

    $application->run(new ArrayInput([
        'command' => 'doctrine:migrations:migrate',
        '--no-interaction' => true,
        '--quiet' => true,
    ]));

    $application->run(new ArrayInput([
        'command' => 'doctrine:fixtures:load',
        '--no-interaction' => true,
        '--quiet' => true,
    ]));

    $kernel->shutdown();
}

// Reset the database before running the tests
if ((bool) ($_SERVER['RESET_TEST_DATABASE'] ?? false) === true) {
    bootstrap();
}
