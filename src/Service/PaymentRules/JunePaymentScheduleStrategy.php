<?php

declare(strict_types=1);

namespace App\Service\PaymentRules;

use App\Entity\Money;
use App\Entity\PaymentSchedule;
use App\Entity\PaymentScheduleItem;
use App\Entity\Product;
use App\Exception\IncorrectProductDateSoldAndScheduleStrategyUsageLogicException;
use DateTimeImmutable;

final class JunePaymentScheduleStrategy implements PaymentScheduleStrategyInterface
{
    public function generateSchedule(Product $product): PaymentSchedule
    {
        if ($product->getDateSold()->format('m') !== '06') {
            throw new IncorrectProductDateSoldAndScheduleStrategyUsageLogicException();
        }

        $paymentSchedule = new PaymentSchedule();
        $paymentSchedule->setProduct($product);

        $firstInstallmentAmount = (int) floor($product->getPrice()->getAmount() * 30 / 100);
        $secondInstallmentAmount = $product->getPrice()->getAmount() - $firstInstallmentAmount;

        $firstInstalment = new Money($firstInstallmentAmount, $product->getPrice()->getCurrency()->value);
        $secondInstalment = new Money($secondInstallmentAmount, $product->getPrice()->getCurrency()->value);

        $secondInstalmentDueDate = (new DateTimeImmutable())->modify('+3 months')->modify('last day of this month');

        $firstInstallment = new PaymentScheduleItem($firstInstalment, new DateTimeImmutable());
        $secondInstallment = new PaymentScheduleItem($secondInstalment, $secondInstalmentDueDate);

        $paymentSchedule->addPaymentScheduleItem($firstInstallment);
        $paymentSchedule->addPaymentScheduleItem($secondInstallment);

        return $paymentSchedule;
    }
}
