<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Trait\AccountOwnableTrait;
use App\Entity\Trait\IdTrait;
use App\Entity\Trait\ImportableTrait;
use App\Entity\Trait\TimestampableTrait;
use App\Entity\Trait\UserOwnableTrait;
use App\Service\Clock\Clock;
use Brick\Money\Currency;
use Brick\Money\Money;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Index(fields: ['userId'])]
#[ORM\Index(fields: ['accountId'])]
#[ORM\Index(fields: ['date'])]
#[ORM\Index(fields: ['adId'])]
#[ORM\UniqueConstraint(fields: ['adId', 'date'])]
class AdStats implements Importable, UserOwnable, AccountOwnable
{
    use AccountOwnableTrait;
    use IdTrait;
    use ImportableTrait;
    use TimestampableTrait;
    use UserOwnableTrait;

    #[ORM\Column(type: UlidType::NAME, unique: false)]
    private Ulid $adId;

    #[ORM\Column]
    private int $results;

    #[ORM\Column]
    private int $costPerResult;

    #[ORM\Column]
    private int $amountSpent;

    #[ORM\Column]
    private string $currency;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private DateTimeInterface $date;

    public function __construct(
        Ulid $id,
        Ulid $userId,
        Ulid $accountId,
        Ulid $adId,
        int $results,
        Money $costPerResult,
        Money $amountSpent,
        DateTimeInterface $date,
    ) {
        $this->userId = $userId;
        $this->adId = $adId;
        $this->results = $results;
        $this->costPerResult = $costPerResult->getMinorAmount()->toInt();
        $this->currency = $amountSpent->getCurrency()->getCurrencyCode();
        $this->amountSpent = $amountSpent->getMinorAmount()->toInt();
        $this->date = $date;
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
        $this->accountId = $accountId;
        $this->id = $id;
    }

    public function getAdId(): Ulid
    {
        return $this->adId;
    }

    public function getResults(): int
    {
        return $this->results;
    }

    public function getCostPerResult(): Money
    {
        return Money::ofMinor($this->costPerResult, $this->getCurrency());
    }

    public function getAmountSpent(): Money
    {
        return Money::ofMinor($this->amountSpent, $this->getCurrency());
    }

    public function getCurrency(): Currency
    {
        return Currency::of($this->currency);
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }
}
