<?php

declare(strict_types=1);

namespace App\Dto;

final class PaymentScheduleResponse
{
    /** @param PaymentScheduleItemResponse[] $schedule */
    public function __construct(public array $schedule)
    {
    }
}
