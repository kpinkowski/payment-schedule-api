<?php

declare(strict_types=1);

namespace App\Service\PaymentRules;

use App\Entity\Product;

final class StandardPaymentScheduleStrategy implements PaymentScheduleStrategyInterface
{
    public function generateSchedule(Product $product): array
    {
        // TODO: Implement generateSchedule() method.
        return [];
    }
}
