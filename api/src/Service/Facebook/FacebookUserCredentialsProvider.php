<?php

declare(strict_types=1);

namespace App\Service\Facebook;

use Symfony\Component\Uid\Ulid;

interface FacebookUserCredentialsProvider
{
    public function getCredentials(Ulid $connectedAccountId): FacebookUserCredentials;
}
