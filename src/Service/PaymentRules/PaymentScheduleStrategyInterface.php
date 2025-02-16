<?php

declare(strict_types=1);

namespace App\Service\PaymentRules;

use App\Entity\PaymentSchedule;
use App\Entity\Product;
use DateTimeInterface;

interface PaymentScheduleStrategyInterface
{
    public function generateSchedule(Product $product, DateTimeInterface $dateSold): PaymentSchedule;
}
