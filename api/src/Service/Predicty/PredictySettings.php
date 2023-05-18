<?php

declare(strict_types=1);

namespace App\Service\Predicty;

/**
 * This service stores settings for the app.
 */
class PredictySettings
{
    public function getCurrentTermsOfServiceVersion(): int
    {
        return 1;
    }
}
