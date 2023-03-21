<?php

declare(strict_types=1);

namespace App\Entity;

enum FileImportType: string
{
    case FACEBOOK_CSV = 'FACEBOOK_CSV';
    case SIMPLIFIED_CSV = 'SIMPLIFIED_CSV';
    case OTHER = 'OTHER';
}
