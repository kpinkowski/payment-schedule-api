<?php

declare(strict_types=1);

namespace App\Service\PaymentRules;

use App\Entity\Money;
use App\Entity\PaymentSchedule;
use App\Entity\PaymentScheduleItem;
use App\Entity\Product;
use App\Exception\IncorrectProductDateSoldAndScheduleStrategyUsageLogicException;
use DateTimeImmutable;

final class JanuaryTwoEqualScheduleStrategy implements PaymentScheduleStrategyInterface
{
    public function generateSchedule(Product $product): PaymentSchedule
    {
        if ($product->getDateSold()->format('m') !== '01') {
            throw new IncorrectProductDateSoldAndScheduleStrategyUsageLogicException();
        }

        $paymentSchedule = new PaymentSchedule();
        $paymentSchedule->setProduct($product);

        $firstInstallmentAmount = (int) floor($product->getPrice()->getAmount() / 2);
        $secondInstallmentAmount = $product->getPrice()->getAmount() - $firstInstallmentAmount;

        $firstInstallment = new Money($firstInstallmentAmount, $product->getPrice()->getCurrency()->value);
        $secondInstallment = new Money($secondInstallmentAmount, $product->getPrice()->getCurrency()->value);

        $firstInstallmentDueDate = (new DateTimeImmutable())->modify('last day of this month');
        $secondInstalmentDueDate = (new DateTimeImmutable())->modify('+1 month')->modify('last day of this month');

        $firstInstallment = new PaymentScheduleItem($firstInstallment, $firstInstallmentDueDate);
        $secondInstallment = new PaymentScheduleItem($secondInstallment, $secondInstalmentDueDate);

        $paymentSchedule->addPaymentScheduleItem($firstInstallment);
        $paymentSchedule->addPaymentScheduleItem($secondInstallment);

        return $paymentSchedule;
    }
}
