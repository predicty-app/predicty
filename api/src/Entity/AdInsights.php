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
class AdInsights implements Importable, UserOwnable, AccountOwnable
{
    use AccountOwnableTrait;
    use IdTrait;
    use ImportableTrait;
    use TimestampableTrait;
    use UserOwnableTrait;

    #[ORM\Column(type: UlidType::NAME, unique: false)]
    private Ulid $adId;

    #[ORM\Column]
    private int $conversions;

    #[ORM\Column]
    private int $costPerResult;

    #[ORM\Column]
    private int $amountSpent;

    #[ORM\Column(options: ['default' => 0])]
    private int $leads;

    #[ORM\Column(options: ['default' => 0])]
    private int $clicks;

    #[ORM\Column(options: ['default' => 0])]
    private int $impressions;

    #[ORM\Column(options: ['default' => 0])]
    private int $costPerClick;

    #[ORM\Column(options: ['default' => 0])]
    private int $costPerMil;

    #[ORM\Column]
    private string $currency;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private DateTimeInterface $date;

    public function __construct(
        Ulid $id,
        Ulid $userId,
        Ulid $accountId,
        Ulid $adId,
        Money $amountSpent,
        DateTimeInterface $date,
        int $conversions = 0,
        int $clicks = 0,
        int $impressions = 0,
        int $leads = 0,
        ?Money $costPerClick = null,
        ?Money $costPerResult = null,
        ?Money $costPerMil = null,
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->accountId = $accountId;
        $this->adId = $adId;
        $this->amountSpent = $amountSpent->getMinorAmount()->toInt();
        $this->date = $date;
        $this->conversions = $conversions;
        $this->clicks = $clicks;
        $this->impressions = $impressions;

        $costPerClick ??= Money::ofMinor(0, $amountSpent->getCurrency());
        $costPerResult ??= Money::ofMinor(0, $amountSpent->getCurrency());
        $costPerMil ??= Money::ofMinor(0, $amountSpent->getCurrency());

        $this->costPerClick = $costPerClick->getMinorAmount()->toInt();
        $this->costPerResult = $costPerResult->getMinorAmount()->toInt();
        $this->costPerMil = $costPerMil->getMinorAmount()->toInt();

        $this->currency = $amountSpent->getCurrency()->getCurrencyCode();
        $this->createdAt = Clock::now();
        $this->changedAt = Clock::now();
        $this->leads = $leads;
    }

    public function getAdId(): Ulid
    {
        return $this->adId;
    }

    public function setConversions(int $conversions): void
    {
        $this->conversions = $conversions;
    }

    public function getConversions(): int
    {
        return $this->conversions;
    }

    public function setCostPerResult(Money $costPerResult): void
    {
        $this->costPerResult = $costPerResult->getMinorAmount()->toInt();
    }

    public function getCostPerResult(): Money
    {
        return Money::ofMinor($this->costPerResult, $this->getCurrency());
    }

    public function setAmountSpent(Money $amountSpent): void
    {
        $this->amountSpent = $amountSpent->getMinorAmount()->toInt();
    }

    public function getAmountSpent(): Money
    {
        return Money::ofMinor($this->amountSpent, $this->getCurrency());
    }

    public function getCurrency(): Currency
    {
        return Currency::of($this->currency);
    }

    public function setLeads(int $leads): void
    {
        $this->leads = $leads;
    }

    public function setClicks(int $clicks): void
    {
        $this->clicks = $clicks;
    }

    public function setImpressions(int $impressions): void
    {
        $this->impressions = $impressions;
    }

    public function setCostPerClick(Money $costPerClick): void
    {
        $this->costPerClick = $costPerClick->getMinorAmount()->toInt();
    }

    public function setCostPerMil(Money $costPerMil): void
    {
        $this->costPerMil = $costPerMil->getMinorAmount()->toInt();
    }

    public function getLeads(): int
    {
        return $this->leads;
    }

    public function getClicks(): int
    {
        return $this->clicks;
    }

    public function getImpressions(): int
    {
        return $this->impressions;
    }

    public function getCostPerClick(): Money
    {
        return Money::ofMinor($this->costPerClick, $this->getCurrency());
    }

    public function getCostPerMil(): Money
    {
        return Money::ofMinor($this->costPerMil, $this->getCurrency());
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }
}
