<?php

declare(strict_types=1);

namespace App\Message;

/**
 * Marker interface for events. Events are always async.
 */
interface Event extends AsyncMessage
{
}
