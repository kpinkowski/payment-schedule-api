<?php

declare(strict_types=1);

namespace App\Service\PaymentRules;

use App\Entity\PaymentSchedule;
use App\Entity\PaymentScheduleItem;
use App\Entity\Product;
use DateTimeInterface;

final class StandardPaymentScheduleStrategy implements PaymentScheduleStrategyInterface
{
    public function generateSchedule(Product $product, DateTimeInterface $dateSold): PaymentSchedule
    {
        $paymentSchedule = new PaymentSchedule();
        $paymentSchedule->setProduct($product);

        $paymentScheduleItem = new PaymentScheduleItem(clone $product->getPrice(), $dateSold);
        $paymentSchedule->addPaymentScheduleItem($paymentScheduleItem);

        return $paymentSchedule;
    }
}
