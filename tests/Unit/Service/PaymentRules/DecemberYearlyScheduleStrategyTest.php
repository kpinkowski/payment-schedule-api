<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\PaymentRules;

use App\Entity\Money;
use App\Entity\Product;
use App\Enum\Currency;
use App\Exception\IncorrectProductDateSoldAndScheduleStrategyUsageLogicException;
use App\Service\PaymentRules\DecemberYearlyScheduleStrategy;
use App\Tests\Common\AssertObject\PaymentScheduleAssertObject;
use App\Tests\Common\TestCase\UnitTestCase;
use DateTimeImmutable;

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
        DateTimeImmutable $dateSold
    ): void {
        $money = new Money($amount, Currency::USD);
        $product = $this->createMock(Product::class);
        $product->method('getPrice')->willReturn($money);
        $product->method('getDateSold')->willReturn($dateSold);

        $schedule = $this->strategy->generateSchedule($product);

        PaymentScheduleAssertObject::assertThat($schedule)
            ->hasProduct($product)
            ->hasInstalmentsNumberEqualTo(12)
            ->installmentIsEqualTo(11, $expectedLastInstalmentAmount);

        for ($i = 0; $i < 10; $i++) {
            PaymentScheduleAssertObject::assertThat($schedule)->installmentIsEqualTo($i, $expectedInstalmentAmount);
        }
    }

    public function testItThrowsExceptionWhenDateSoldIsWrong(): void
    {
        $dateSold = new DateTimeImmutable('2024-01-01');
        $product = $this->createMock(Product::class);
        $product->method('getDateSold')->willReturn($dateSold);

        $this->expectException(IncorrectProductDateSoldAndScheduleStrategyUsageLogicException::class);
        $this->strategy->generateSchedule($product);
    }

    public function correctProductDataProvider(): array
    {
        return [
            [1200, 100, 100, new DateTimeImmutable('2024-12-01')],
            [6293, 524, 529, new DateTimeImmutable('2024-12-01')],
        ];
    }
}
