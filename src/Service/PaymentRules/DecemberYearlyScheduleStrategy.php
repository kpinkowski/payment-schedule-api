<?php

declare(strict_types=1);

namespace App\Service\PaymentRules;

use App\Entity\PaymentSchedule;
use App\Entity\PaymentScheduleItem;
use App\Entity\Product;
use App\Exception\IncorrectProductDateSoldAndScheduleStrategyUsageLogicException;
use DateTimeInterface;
use DateTime;

final class DecemberYearlyScheduleStrategy implements PaymentScheduleStrategyInterface
{
    private const NUMBER_OF_INSTALMENTS = 12;

    public function generateSchedule(Product $product, DateTimeInterface $dateSold): PaymentSchedule
    {
        if ($dateSold->format('m') !== '12') {
            throw new IncorrectProductDateSoldAndScheduleStrategyUsageLogicException();
        }

        $paymentSchedule = new PaymentSchedule();
        $paymentSchedule->setProduct($product);

        $totalPrice = $product->getPrice();
        $baseInstallmentAmount = (int) floor($totalPrice / self::NUMBER_OF_INSTALMENTS);
        $lastInstallmentAmount = $totalPrice - ($baseInstallmentAmount * (self::NUMBER_OF_INSTALMENTS - 1));

        for ($i = 0; $i < self::NUMBER_OF_INSTALMENTS - 1; $i++) {
            $paymentScheduleItem = new PaymentScheduleItem($baseInstallmentAmount, new DateTime("+{$i} months"));
            $paymentSchedule->addPaymentScheduleItem($paymentScheduleItem);
        }

        $lastPaymentScheduleItem = new PaymentScheduleItem($lastInstallmentAmount, new DateTime("+11 months"));
        $paymentSchedule->addPaymentScheduleItem($lastPaymentScheduleItem);

        return $paymentSchedule;
    }
}
