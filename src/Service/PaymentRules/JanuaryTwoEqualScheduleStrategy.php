<?php

declare(strict_types=1);

namespace App\Service\PaymentRules;

use App\Entity\PaymentSchedule;
use App\Entity\PaymentScheduleItem;
use App\Entity\Product;
use App\Exception\IncorrectProductDateSoldAndScheduleStrategyUsageLogicException;
use DateTime;
use DateTimeInterface;

final class JanuaryTwoEqualScheduleStrategy implements PaymentScheduleStrategyInterface
{
    public function generateSchedule(Product $product, DateTimeInterface $dateSold): PaymentSchedule
    {
        if ($dateSold->format('m') !== '01') {
            throw new IncorrectProductDateSoldAndScheduleStrategyUsageLogicException();
        }

        $paymentSchedule = new PaymentSchedule();
        $paymentSchedule->setProduct($product);

        $firstInstallmentAmount = (int) floor($product->getPrice() / 2);
        $secondInstallmentAmount = $product->getPrice() - $firstInstallmentAmount;

        $firstInstallmentDueDate = (new DateTime())->modify('last day of this month');
        $secondInstalmentDueDate = (new DateTime())->modify('+1 month')->modify('last day of this month');

        $firstInstallment = new PaymentScheduleItem($firstInstallmentAmount, $firstInstallmentDueDate);
        $secondInstallment = new PaymentScheduleItem($secondInstallmentAmount, $secondInstalmentDueDate);

        $paymentSchedule->addPaymentScheduleItem($firstInstallment);
        $paymentSchedule->addPaymentScheduleItem($secondInstallment);

        return $paymentSchedule;
    }
}
