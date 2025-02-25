<?php

declare(strict_types=1);

namespace App\Messenger\Query;

final class GetPaymentScheduleQuery
{
    public function __construct(private readonly int $paymentScheduleId)
    {
    }

    public function getPaymentScheduleId(): int
    {
        return $this->paymentScheduleId;
    }
}
