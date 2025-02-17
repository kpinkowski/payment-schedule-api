<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\PaymentRules;

use App\Entity\Money;
use App\Entity\Product;
use App\Exception\IncorrectProductDateSoldAndScheduleStrategyUsageLogicException;
use App\Service\PaymentRules\JanuaryTwoEqualScheduleStrategy;
use App\Tests\Common\AssertObject\PaymentScheduleAssertObject;
use App\Tests\Common\TestCase\UnitTestCase;
use DateTime;
use DateTimeInterface;

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
        DateTimeInterface $dateSold
    ): void {
        $money = new Money($amount, 'USD');
        $product = $this->createMock(Product::class);
        $product->method('getPrice')->willReturn($money);

        $schedule = $this->strategy->generateSchedule($product, $dateSold);

        PaymentScheduleAssertObject::assertThat($schedule)
            ->hasProduct($product)
            ->hasSameTotalAmountAsProduct($product)
            ->hasInstalmentsNumberEqualTo(2)
            ->installmentIsEqualTo(0, $expectedFirstInstalmentAmount)
            ->installmentIsEqualTo(1, $expectedSecondInstalmentAmount);
    }

    public function testItThrowsExceptionWhenDateSoldIsWrong(): void
    {
        $product = $this->createMock(Product::class);
        $dateSold = new DateTime('2024-12-01');

        $this->expectException(IncorrectProductDateSoldAndScheduleStrategyUsageLogicException::class);
        $this->strategy->generateSchedule($product, $dateSold);
    }

    public function correctProductDataProvider(): array
    {
        return [
            [1200, 600, 600, new DateTime('2024-01-01')],
            [1201, 600, 601, new DateTime('2024-01-01')],
        ];
    }
}
