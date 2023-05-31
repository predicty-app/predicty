<?php

declare(strict_types=1);

namespace App\Service\Security\Account\Storage;

use App\Service\Security\Account\AccountSwitcherStorage;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Uid\Ulid;

class SessionStorage implements AccountSwitcherStorage
{
    private const ACCOUNT_SWITCHER_SESSION_KEY = 'account_switcher';

    public function __construct(private RequestStack $requestStack)
    {
    }

    public function set(Ulid $userId, Ulid $accountId): void
    {
        $this->requestStack->getSession()->set(self::ACCOUNT_SWITCHER_SESSION_KEY, [
            'user_id' => (string) $userId,
            'account_id' => (string) $accountId,
        ]);
    }

    public function get(Ulid $userId): ?Ulid
    {
        $data = $this->requestStack->getSession()->get(self::ACCOUNT_SWITCHER_SESSION_KEY, ['user_id' => null, 'account_id' => null]);

        if ($data['user_id'] === (string) $userId) {
            return Ulid::fromString($data['account_id']);
        }

        return null;
    }
}
