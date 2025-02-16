<?php

declare(strict_types=1);

namespace App\Messenger\Command;

use App\Entity\Product;

final class CalculatePaymentScheduleCommand
{
    public function __construct(private readonly Product $product)
    {
    }

    public function getProduct(): Product
    {
        return $this->product;
    }
}
