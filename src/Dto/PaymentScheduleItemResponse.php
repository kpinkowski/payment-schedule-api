<?php

declare(strict_types=1);

namespace App\Dto;

use OpenApi\Attributes as OA;

final class PaymentScheduleItemResponse
{
    #[OA\Property(description: "Amount stored as integer. 9.99$ is represented as 999, so amount is in cents.", example: 100)]
    public readonly int $amount;

    #[OA\Property(description: "Currency code of payment", type: "string", enum: ["USD", "PLN", "EUR"], example: "USD")]
    public readonly string $currency;

    #[OA\Property(description: "Due date of payment in UTC time", type: "string", format: "date", example: "2022-12-31 23:59:59")]
    public readonly string $dueDate;

    public function __construct(
        int $amount,
        string $currency,
        string $dueDate,
    ) {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->dueDate = $dueDate;
    }
}
