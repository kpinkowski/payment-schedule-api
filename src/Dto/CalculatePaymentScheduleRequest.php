<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enum\ProductType;
use App\Entity\Money;
use Symfony\Component\Validator\Constraints as Assert;
use App\Messenger\Command\CalculatePaymentScheduleCommand;
use DateTimeImmutable;

final class CalculatePaymentScheduleRequest
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(min: 3, max: 255)]
    public string $productName;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Choice(callback: [ProductType::class, 'values'])]
    public string $productType;

    #[Assert\NotBlank]
    #[Assert\Type(Money::class)]
    public Money $productPrice;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Date]
    public string $productDateSold;

    public function toCommand(): CalculatePaymentScheduleCommand
    {
        return new CalculatePaymentScheduleCommand(
            ProductType::from($this->productType),
            $this->productName,
            $this->productPrice,
            new DateTimeImmutable($this->productDateSold)
        );
    }
}
