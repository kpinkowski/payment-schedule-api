<?php

declare(strict_types=1);

namespace App\Service\PaymentRules;

use App\Entity\PaymentSchedule;
use App\Entity\PaymentScheduleItem;
use App\Entity\Product;

final class StandardPaymentScheduleStrategy implements PaymentScheduleStrategyInterface
{
    public function generateSchedule(Product $product): PaymentSchedule
    {
        $paymentSchedule = new PaymentSchedule();
        $paymentSchedule->setProduct($product);

        $paymentScheduleItem = new PaymentScheduleItem(clone $product->getPrice(), $product->getDateSold());
        $paymentSchedule->addPaymentScheduleItem($paymentScheduleItem);

        return $paymentSchedule;
    }
}
