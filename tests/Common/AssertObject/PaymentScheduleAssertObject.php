<?php

declare(strict_types=1);

namespace App\Tests\Common\AssertObject;

use App\Entity\PaymentSchedule;
use App\Entity\Product;
use PHPUnit\Framework\Assert;

final class PaymentScheduleAssertObject
{
    private const INVALID_NUMBER_OF_INSTALMENTS = 'Invalid number of instalments';
    private const INVALID_PRODUCT = 'Invalid product';
    private const INVALID_TOTAL_AMOUNT = 'Invalid total amount';
    private const INVALID_INSTALLMENT_AMOUNT = 'Invalid installment amount';
    private const INSTALLMENT_WITH_INDEX_DOES_NOT_EXIST = 'Instalment with index does not exist';
    private const INVALID_INSTALLMENT_DATE = 'Invalid installment date';

    public static function assertThat(PaymentSchedule $paymentSchedule): self
    {
        return new self($paymentSchedule);
    }

    public function __construct(private readonly PaymentSchedule $paymentSchedule)
    {
    }

    public function hasInstalmentsNumberEqualTo(int $instalments): self
    {
        Assert::assertCount(
            $instalments,
            $this->paymentSchedule->getPaymentScheduleItems(),
            self::INVALID_NUMBER_OF_INSTALMENTS
        );

        return $this;
    }

    public function hasSameTotalAmountAsProduct(Product $product): self
    {
        Assert::assertSame(
            $product->getPrice()->getAmount(),
            $this->paymentSchedule->getTotalAmount()->getAmount(),
            self::INVALID_TOTAL_AMOUNT
        );

        $instalmentsTotal = 0;

        foreach ($this->paymentSchedule->getPaymentScheduleItems() as $paymentScheduleItem) {
            $instalmentsTotal += $paymentScheduleItem->getAmount()->getAmount();
        }

        Assert::assertSame(
            $product->getPrice()->getAmount(),
            $instalmentsTotal,
            self::INVALID_TOTAL_AMOUNT
        );

        return $this;
    }

    public function hasProduct(Product $product): self
    {
        Assert::assertSame(
            $product,
            $this->paymentSchedule->getProduct(),
            self::INVALID_PRODUCT
        );

        return $this;
    }

    public function installmentIsEqualTo(int $index, int $amount): self
    {
        Assert::assertArrayHasKey(
            $index,
            $this->paymentSchedule->getPaymentScheduleItems(),
            self::INSTALLMENT_WITH_INDEX_DOES_NOT_EXIST
        );

        Assert::assertNotNull(
            $this->paymentSchedule->getPaymentScheduleItems()[$index],
            self::INSTALLMENT_WITH_INDEX_DOES_NOT_EXIST
        );

        Assert::assertSame(
            $amount,
            $this->paymentSchedule->getPaymentScheduleItems()[$index]->getAmount()->getAmount(),
            self::INVALID_INSTALLMENT_AMOUNT
        );

        return $this;
    }
}
