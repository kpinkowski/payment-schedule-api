<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\PaymentRules;

use App\Entity\Money;
use App\Entity\Product;
use App\Enum\Currency;
use App\Enum\ProductType;
use App\Exception\IncorrectProductDateSoldAndScheduleStrategyUsageLogicException;
use App\Exception\IncorrectProductTypeAndScheduleStrategyUsageLogicException;
use App\Service\PaymentRules\CarProductTypeTwoEqualScheduleStrategy;
use App\Tests\Common\AssertObject\PaymentScheduleAssertObject;
use App\Tests\Common\TestCase\UnitTestCase;
use DateTimeImmutable;

final class CarProductTypeTwoEqualScheduleStrategyTest extends UnitTestCase
{
    private CarProductTypeTwoEqualScheduleStrategy $strategy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->strategy = new CarProductTypeTwoEqualScheduleStrategy();
    }

    /**
     * @dataProvider correctProductDataProvider
     */
    public function testItDoesCalculatePaymentSchedule(
        int $amount,
        int $expectedFirstInstalmentAmount,
        int $expectedSecondInstalmentAmount,
        DateTimeImmutable $dateSold,
        ProductType $productType
    ): void {
        $money = new Money($amount, Currency::USD->value);
        $product = $this->createMock(Product::class);
        $product->method('getPrice')->willReturn($money);
        $product->method('getDateSold')->willReturn($dateSold);
        $product->method('getProductType')->willReturn($productType);

        $schedule = $this->strategy->generateSchedule($product);

        PaymentScheduleAssertObject::assertThat($schedule)
            ->hasProduct($product)
            ->hasInstalmentsNumberEqualTo(2)
            ->installmentIsEqualTo(0, $expectedFirstInstalmentAmount)
            ->installmentIsEqualTo(1, $expectedSecondInstalmentAmount);
    }

    public function testItThrowsExceptionWhenDateSoldIsWrong(): void
    {
        $product = $this->createMock(Product::class);
        $productType = ProductType::ELECTRONICS;
        $product->method('getProductType')->willReturn($productType);

        $this->expectException(IncorrectProductTypeAndScheduleStrategyUsageLogicException::class);
        $this->strategy->generateSchedule($product);
    }

    public function correctProductDataProvider(): array
    {
        return [
            [1200, 600, 600, new DateTimeImmutable('2024-06-01'), ProductType::CARS],
            [1201, 600, 601, new DateTimeImmutable('2024-12-01'), ProductType::CARS],
        ];
    }
}
