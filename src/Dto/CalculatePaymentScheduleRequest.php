<?php

declare(strict_types=1);

namespace App\Dto;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
use App\Enum\ProductType;
use App\Entity\Money;
use App\Messenger\Command\CalculatePaymentScheduleCommand;
use DateTimeImmutable;

#[OA\Schema(title: 'CalculatePaymentScheduleRequest')]
final class CalculatePaymentScheduleRequest
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(min: 3, max: 255)]
    #[OA\Property(example: "iPhone 13 Pro Max")]
    public string $productName;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Choice(callback: [ProductType::class, 'values'])]
    #[OA\Property(enum: ["electronics", "cars", "furniture"], example: "electronics")]
    public string $productType;

    #[Assert\NotBlank]
    #[Assert\Type(Money::class)]
    public Money $productPrice;

    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\DateTime(format: "Y-m-d\TH:i:sP", message: "Date has to be in ISO 8601 format.")]
    #[OA\Property(example: "2025-02-18T00:00:00+00:00")]
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
