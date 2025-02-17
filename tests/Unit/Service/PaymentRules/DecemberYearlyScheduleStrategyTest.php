<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\PaymentRules;

use App\Entity\Money;
use App\Entity\Product;
use App\Exception\IncorrectProductDateSoldAndScheduleStrategyUsageLogicException;
use App\Service\PaymentRules\DecemberYearlyScheduleStrategy;
use App\Tests\Common\AssertObject\PaymentScheduleAssertObject;
use App\Tests\Common\TestCase\UnitTestCase;
use DateTime;
use DateTimeInterface;

final class DecemberYearlyScheduleStrategyTest extends UnitTestCase
{
    private DecemberYearlyScheduleStrategy $strategy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->strategy = new DecemberYearlyScheduleStrategy();
    }

    /**
     * @dataProvider correctProductDataProvider
     */
    public function testItDoesCalculatePaymentSchedule(
        int $amount,
        int $expectedInstalmentAmount,
        int $expectedLastInstalmentAmount,
        DateTimeInterface $dateSold
    ): void {
        $money = new Money($amount, 'USD');
        $product = $this->createMock(Product::class);
        $product->method('getPrice')->willReturn($money);

        $schedule = $this->strategy->generateSchedule($product, $dateSold);

        PaymentScheduleAssertObject::assertThat($schedule)
            ->hasProduct($product)
            ->hasSameTotalAmountAsProduct($product)
            ->hasInstalmentsNumberEqualTo(12)
            ->installmentIsEqualTo(11, $expectedLastInstalmentAmount);

        for ($i = 0; $i < 10; $i++) {
            PaymentScheduleAssertObject::assertThat($schedule)->installmentIsEqualTo($i, $expectedInstalmentAmount);
        }
    }

    public function testItThrowsExceptionWhenDateSoldIsWrong(): void
    {
        $product = $this->createMock(Product::class);
        $dateSold = new DateTime('2024-01-01');

        $this->expectException(IncorrectProductDateSoldAndScheduleStrategyUsageLogicException::class);
        $this->strategy->generateSchedule($product, $dateSold);
    }

    public function correctProductDataProvider(): array
    {
        return [
            [1200, 100, 100, new DateTime('2024-12-01')],
            [6293, 524, 529, new DateTime('2024-12-01')],
        ];
    }
}
