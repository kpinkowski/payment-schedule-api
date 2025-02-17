<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

#[ORM\Embeddable]
class Money
{
    #[ORM\Column(type: "integer")]
    #[ApiProperty(openapiContext: ["type" => "integer", "example" => 100000])]
    private int $amount;

    #[ORM\Column(type: "string", length: 3)]
    #[ApiProperty(openapiContext: ["type" => "string", "example" => "USD"])]
    private string $currency;

    public function __construct(int $amount, string $currency)
    {
        if ($amount < 0) {
            throw new InvalidArgumentException("Amount cannot be negative.");
        }

        $this->amount = $amount;
        $this->currency = strtoupper($currency);
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
