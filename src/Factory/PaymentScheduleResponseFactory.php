<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\PaymentScheduleItemResponse;
use App\Dto\PaymentScheduleResponse;
use App\Entity\PaymentSchedule;

final class PaymentScheduleResponseFactory
{
    public function create(PaymentSchedule $schedule): PaymentScheduleResponse
    {
        $items = [];

        foreach ($schedule->getPaymentScheduleItems() as $item) {
            $items[] = new PaymentScheduleItemResponse(
                $item->getAmount()->getAmount(),
                $item->getAmount()->getCurrency()->value,
                $item->getDueDate()->format('Y-m-d H:i:s')
            );
        }

        return new PaymentScheduleResponse($items);
    }
}
