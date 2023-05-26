<?php

declare(strict_types=1);

namespace App\Service\Facebook;

interface FacebookUserCredentials
{
    public function getAccessToken(): string;

    public function getAdAccountId(): string;
}
