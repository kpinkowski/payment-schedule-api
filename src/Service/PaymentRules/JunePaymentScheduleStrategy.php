<?php

declare(strict_types=1);

namespace App\Service\PaymentRules;

use App\Entity\Money;
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

        $firstInstallmentAmount = (int) floor($product->getPrice()->getAmount() * 30 / 100);
        $secondInstallmentAmount = $product->getPrice()->getAmount() - $firstInstallmentAmount;

        $firstInstalment = new Money($firstInstallmentAmount, $product->getPrice()->getCurrency());
        $secondInstalment = new Money($secondInstallmentAmount, $product->getPrice()->getCurrency());

        $secondInstalmentDueDate = (new DateTime())->modify('+3 months')->modify('last day of this month');

        $firstInstallment = new PaymentScheduleItem($firstInstalment, new DateTimeImmutable());
        $secondInstallment = new PaymentScheduleItem($secondInstalment, $secondInstalmentDueDate);

        $paymentSchedule->addPaymentScheduleItem($firstInstallment);
        $paymentSchedule->addPaymentScheduleItem($secondInstallment);

        return $paymentSchedule;
    }
}
