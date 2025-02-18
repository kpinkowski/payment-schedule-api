<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\PaymentRules;

use App\Entity\Money;
use App\Entity\Product;
use App\Enum\Currency;
use App\Exception\IncorrectProductDateSoldAndScheduleStrategyUsageLogicException;
use App\Service\PaymentRules\JanuaryTwoEqualScheduleStrategy;
use App\Tests\Common\AssertObject\PaymentScheduleAssertObject;
use App\Tests\Common\TestCase\UnitTestCase;
use DateTimeImmutable;

final class JanuaryTwoEqualScheduleStrategyTest extends UnitTestCase
{
    private JanuaryTwoEqualScheduleStrategy $strategy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->strategy = new JanuaryTwoEqualScheduleStrategy();
    }

    /**
     * @dataProvider correctProductDataProvider
     */
    public function testItDoesCalculatePaymentSchedule(
        int $amount,
        int $expectedFirstInstalmentAmount,
        int $expectedSecondInstalmentAmount,
        DateTimeImmutable $dateSold
    ): void {
        $money = new Money($amount, Currency::USD->value);
        $product = $this->createMock(Product::class);
        $product->method('getPrice')->willReturn($money);
        $product->method('getDateSold')->willReturn($dateSold);

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
        $dateSold = new DateTimeImmutable('2024-12-01');
        $product->method('getDateSold')->willReturn($dateSold);

        $this->expectException(IncorrectProductDateSoldAndScheduleStrategyUsageLogicException::class);
        $this->strategy->generateSchedule($product);
    }

    public function correctProductDataProvider(): array
    {
        return [
            [1200, 600, 600, new DateTimeImmutable('2024-01-01')],
            [1201, 600, 601, new DateTimeImmutable('2024-01-01')],
        ];
    }
}
