<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Facebook;

use App\Service\Facebook\FacebookCsvImporter;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class FacebookCsvImporterTest extends TestCase
{
    public function test_import(): void
    {
        $importer = new FacebookCsvImporter();
        $importer->import(__DIR__.'/FB-groupped.csv');
    }
}
