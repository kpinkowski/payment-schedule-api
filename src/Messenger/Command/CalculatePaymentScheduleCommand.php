<?php

declare(strict_types=1);

namespace App\Messenger\Command;

use App\Entity\Product;
use DateTimeImmutable;
use DateTimeInterface;

final class CalculatePaymentScheduleCommand
{
    private DateTimeInterface $dateSold;

    public function __construct(
        private readonly Product $product,
        string $dateSold
    ) {
        $this->dateSold = new DateTimeImmutable($dateSold);
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getDateSold(): DateTimeImmutable
    {
        return $this->dateSold;
    }
}
