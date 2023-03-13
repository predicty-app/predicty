<?php

declare(strict_types=1);

namespace App\Entity;

enum FileImportType: string
{
    case FACEBOOK_CSV = 'FACEBOOK_CSV';
}
