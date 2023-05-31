<?php

declare(strict_types=1);

namespace App\Service\Security\PasswordReset;

use App\Entity\User;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Uid\Ulid;

/**
 * @internal
 */
class PasswordResetService implements PasswordResetTokenValidator, PasswordResetTokenGenerator
{
    private const CACHE_PREFIX = 'PasswordResetService';
    private const CACHE_TTL = 3600;

    public function __construct(private string $secret, private CacheInterface $cache)
    {
    }

    public function createToken(User $user): string
    {
        $token = $this->hashToken($this->generateRandomToken());
        $this->cache->set($this->getCacheKey($token), (string) $user->getId(), self::CACHE_TTL);

        return $token;
    }

    public function validateAndGetUserId(string $token): ?Ulid
    {
        $userId = $this->cache->get($this->getCacheKey($token));

        if ($userId !== null) {
            $this->cache->delete($this->getCacheKey($token));

            return Ulid::fromString($userId);
        }

        return null;
    }

    private function getCacheKey(string $token): string
    {
        return sprintf('%s_%s', self::CACHE_PREFIX, $token);
    }

    private function hashToken(string $token): string
    {
        return hash_hmac('sha256', $token, $this->secret);
    }

    private function generateRandomToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}
