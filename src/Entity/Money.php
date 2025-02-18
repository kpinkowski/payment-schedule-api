<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\Currency;
use App\Exception\AmountCannotBeNegativeException;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;

#[ORM\Embeddable]
class Money
{
    #[ORM\Column(type: "integer")]
    #[OA\Property(description: "Amount stored as integer. 9.99$ is represented as 999, so amount is in cents.", example: 100)]
    private int $amount;

    #[ORM\Column(type: "string", length: 3)]
    #[OA\Property(enum: ['USD', 'PLN', 'EUR'], example: "USD")]
    private string $currency;

    public function __construct(int $amount, string $currency)
    {
        if ($amount < 0) {
            throw new AmountCannotBeNegativeException();
        }

        $this->amount = $amount;
        $this->currency = strtoupper($currency);
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
