<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\AccountInvitation;
use App\Entity\UserWithId;
use App\Service\Clock\Clock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\MockClock;
use Symfony\Component\Uid\Ulid;

/**
 * @covers \App\Entity\AccountInvitation
 */
class AccountInvitationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $now = Clock::now();
        Clock::set(new MockClock($now));
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Clock::reset();
    }

    public function test_get_user_id(): void
    {
        $invitation = $this->createInvitation();
        $this->assertEquals('01H55F3C53DZCVTWHJ0KWBMQEQ', $invitation->getUserId());
    }

    public function test_get_created_at(): void
    {
        $invitation = $this->createInvitation();
        $this->assertEquals(Clock::now(), $invitation->getCreatedAt());
    }

    public function test_get_changed_at(): void
    {
        $invitation = $this->createInvitation();
        $this->assertEquals(Clock::now(), $invitation->getChangedAt());
    }

    public function test_get_valid_to(): void
    {
        $invitation = $this->createInvitation();
        $this->assertEquals(Clock::now(), $invitation->getValidTo());
    }

    public function test_get_id(): void
    {
        $invitation = $this->createInvitation();
        $this->assertEquals('01H55F317Z22E5ZG0ASAFAMQEM', $invitation->getId());
    }

    public function test_belongs_to_same_account(): void
    {
        $invitation = $this->createInvitation();
        $this->assertTrue($invitation->belongsToAccount(new Ulid('01H55F3C53DZCVTWHJ0KWBMQEQ')));
    }

    public function test_get_email(): void
    {
        $invitation = $this->createInvitation();
        $this->assertEquals('john.doe@example.com', $invitation->getEmail());
    }

    public function test_is_owned_by(): void
    {
        $user = new class() implements UserWithId {
            public function getId(): Ulid
            {
                return new Ulid('01H55F3C53DZCVTWHJ0KWBMQEQ');
            }
        };

        $invitation = $this->createInvitation();
        $this->assertTrue($invitation->isOwnedBy($user));
    }

    public function test_belongs_to_account(): void
    {
        $invitation = $this->createInvitation();
        $this->assertTrue($invitation->belongsToAccount(new Ulid('01H55F3C53DZCVTWHJ0KWBMQEQ')));
    }

    public function test_get_account_id(): void
    {
        $invitation = $this->createInvitation();
        $this->assertEquals('01H55F3C53DZCVTWHJ0KWBMQEQ', $invitation->getAccountId());
    }

    private function createInvitation(): AccountInvitation
    {
        return new AccountInvitation(
            new Ulid('01H55F317Z22E5ZG0ASAFAMQEM'),
            new Ulid('01H55F3C53DZCVTWHJ0KWBMQEQ'),
            new Ulid('01H55F3C53DZCVTWHJ0KWBMQEQ'),
            'john.doe@example.com',
            Clock::now()
        );
    }
}
