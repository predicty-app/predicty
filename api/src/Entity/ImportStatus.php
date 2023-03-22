<?php

declare(strict_types=1);

namespace App\Entity;

enum ImportStatus: string
{
    case WAITING = 'WAITING';
    case IN_PROGRESS = 'IN_PROGRESS';
    case COMPLETE = 'COMPLETE';
    case FAILED = 'FAILED';
}
