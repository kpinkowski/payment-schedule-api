<?php

declare(strict_types=1);

namespace App\Service\PaymentRules;

use App\Entity\Money;
use App\Entity\PaymentSchedule;
use App\Entity\PaymentScheduleItem;
use App\Entity\Product;
use App\Exception\IncorrectProductDateSoldAndScheduleStrategyUsageLogicException;
use DateTime;

final class DecemberYearlyScheduleStrategy implements PaymentScheduleStrategyInterface
{
    private const NUMBER_OF_INSTALMENTS = 12;

    public function generateSchedule(Product $product): PaymentSchedule
    {
        if ($product->getDateSold()->format('m') !== '12') {
            throw new IncorrectProductDateSoldAndScheduleStrategyUsageLogicException();
        }

        $paymentSchedule = new PaymentSchedule();
        $paymentSchedule->setProduct($product);

        $totalPrice = $product->getPrice()->getAmount();
        $baseInstallmentAmount = (int) floor($totalPrice / self::NUMBER_OF_INSTALMENTS);
        $lastInstallmentAmount = $totalPrice - ($baseInstallmentAmount * (self::NUMBER_OF_INSTALMENTS - 1));

        $baseInstallment = new Money($baseInstallmentAmount, $product->getPrice()->getCurrency());
        $lastInstallment = new Money($lastInstallmentAmount, $product->getPrice()->getCurrency());

        for ($i = 0; $i < self::NUMBER_OF_INSTALMENTS - 1; $i++) {
            $paymentScheduleItem = new PaymentScheduleItem($baseInstallment, new DateTime("+{$i} months"));
            $paymentSchedule->addPaymentScheduleItem($paymentScheduleItem);
        }

        $lastPaymentScheduleItem = new PaymentScheduleItem($lastInstallment, new DateTime("+11 months"));
        $paymentSchedule->addPaymentScheduleItem($lastPaymentScheduleItem);

        return $paymentSchedule;
    }
}
