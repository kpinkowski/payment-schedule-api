<?php

declare(strict_types=1);

namespace App\Messenger\Command;

use App\Entity\Money;
use App\Enum\ProductType;
use DateTimeImmutable;

final class CalculatePaymentScheduleCommand
{
    public function __construct(
        private readonly ProductType $productType,
        private readonly string $productName,
        private readonly Money $productPrice,
        private readonly DateTimeImmutable $dateSold,
    ) {
    }

    public function getProductType(): ProductType
    {
        return $this->productType;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getProductPrice(): Money
    {
        return $this->productPrice;
    }

    public function getDateSold(): DateTimeImmutable
    {
        return $this->dateSold;
    }
}
