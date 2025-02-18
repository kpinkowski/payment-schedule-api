<?php

declare(strict_types=1);

namespace App\Dto;

final class PaymentScheduleItemResponse
{
    public function __construct(
        public readonly int $amount,
        public readonly string $currency,
        public readonly string $dueDate,
    ) {
    }
}
