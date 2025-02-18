<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\Currency;
use App\Exception\AmountCannotBeNegativeException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Money
{
    #[ORM\Column(type: "integer")]
    private int $amount;

    #[ORM\Column(type: "string", length: 3)]
    private string $currency;

    public function __construct(int $amount, Currency $currency)
    {
        if ($amount < 0) {
            throw new AmountCannotBeNegativeException();
        }

        $this->amount = $amount;
        $this->currency = strtoupper($currency->value);
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return Currency::from($this->currency);
    }
}
