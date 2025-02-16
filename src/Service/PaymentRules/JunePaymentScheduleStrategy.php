<?php

declare(strict_types=1);

namespace App\Service\PaymentRules;

use App\Entity\PaymentSchedule;
use App\Entity\PaymentScheduleItem;
use App\Entity\Product;
use App\Exception\IncorrectProductDateSoldAndScheduleStrategyUsageLogicException;
use DateTimeInterface;
use DateTimeImmutable;
use DateTime;

final class JunePaymentScheduleStrategy implements PaymentScheduleStrategyInterface
{
    public function generateSchedule(Product $product, DateTimeInterface $dateSold): PaymentSchedule
    {
        if ($dateSold->format('m') !== '06') {
            throw new IncorrectProductDateSoldAndScheduleStrategyUsageLogicException();
        }

        $paymentSchedule = new PaymentSchedule();
        $paymentSchedule->setProduct($product);
        $paymentSchedule->setTotalAmount($product->getPrice());

        $firstInstallmentAmount = (int) floor($product->getPrice() * 30 / 100);
        $secondInstallmentAmount = $product->getPrice() - $firstInstallmentAmount;

        $secondInstalmentDueDate = (new DateTime())->modify('+3 months')->modify('last day of this month');

        $firstInstallment = new PaymentScheduleItem($firstInstallmentAmount, new DateTimeImmutable());
        $secondInstallment = new PaymentScheduleItem($secondInstallmentAmount, $secondInstalmentDueDate);

        $paymentSchedule->addPaymentScheduleItem($firstInstallment);
        $paymentSchedule->addPaymentScheduleItem($secondInstallment);

        return $paymentSchedule;
    }
}
