<?php

declare(strict_types=1);

namespace App\Service\Security;

use App\Entity\User;
use Psr\SimpleCache\CacheInterface;

/**
 * @internal
 */
class CacheBasedPasscodeService implements PasscodeVerifier, PasscodeGenerator
{
    private const ENV_DEV = 'dev';
    private const ENV_TEST = 'test';
    private const CACHE_PREFIX = 'PasscodeService_';

    public function __construct(private CacheInterface $cache, private ?string $env = null)
    {
    }

    public function generate(User $user, int $ttl = 300): string
    {
        $code = $this->generatePasscode();
        $this->cache->set(self::key($user), $code, $ttl);

        return $code;
    }

    public function verify(User $user, string $code): bool
    {
        if ($this->env === self::ENV_DEV && $code === '111111') {
            return true;
        }

        $codeFromCache = $this->cache->get(self::key($user));

        if ($code === $codeFromCache) {
            $this->cache->delete(self::key($user));

            return true;
        }

        return false;
    }

    private function generatePasscode(): string
    {
        return (string) random_int(100000, 999999);
    }

    private static function key(User $user): string
    {
        return self::CACHE_PREFIX.((string) $user->getUuid());
    }
}
