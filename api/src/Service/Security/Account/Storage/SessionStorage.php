<?php

declare(strict_types=1);

namespace App\Service\Security\Account\Storage;

use App\Service\Security\Account\AccountSwitcherStorage;
use Symfony\Component\HttpFoundation\RequestStack;

class SessionStorage implements AccountSwitcherStorage
{
    private const ACCOUNT_SWITCHER_SESSION_KEY = 'account_switcher';

    public function __construct(private RequestStack $requestStack)
    {
    }

    public function set(int $userId, int $accountId): void
    {
        $this->requestStack->getSession()->set(self::ACCOUNT_SWITCHER_SESSION_KEY, [
            'user_id' => $userId,
            'account_id' => $accountId,
        ]);
    }

    public function get(int $userId): ?int
    {
        $data = $this->requestStack->getSession()->get(self::ACCOUNT_SWITCHER_SESSION_KEY, ['user_id' => null, 'account_id' => null]);

        if ($data['user_id'] === $userId) {
            return $data['account_id'];
        }

        return null;
    }
}
