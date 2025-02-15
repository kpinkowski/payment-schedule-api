<?php

declare(strict_types=1);

namespace App\Service\PaymentRules;

use App\Entity\Product;

interface PaymentScheduleStrategyInterface
{
    public function generateSchedule(Product $product): array;
}
