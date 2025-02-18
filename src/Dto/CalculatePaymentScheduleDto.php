<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enum\ProductType;
use App\Entity\Money;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;
use App\Messenger\Command\CalculatePaymentScheduleCommand;
use DateTimeImmutable;

final class CalculatePaymentScheduleDto
{
    #[Assert\NotBlank]
    #[Assert\Type(ProductType::class)]
    public readonly ProductType $productType;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(min: 3, max: 255)]
    public readonly string $productName;

    #[Assert\NotBlank]
    #[Assert\Type(Money::class)]
    public readonly Money $productPrice;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Date]
    #[SerializedName('productSoldDate')]
    public readonly string $productSoldDate;

    public function toCommand(): CalculatePaymentScheduleCommand
    {
        return new CalculatePaymentScheduleCommand(
            $this->productType,
            $this->productName,
            $this->productPrice,
            new DateTimeImmutable($this->productSoldDate)
        );
    }
}
