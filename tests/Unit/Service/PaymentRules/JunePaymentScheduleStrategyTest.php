<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\PaymentRules;

use App\Entity\Money;
use App\Entity\Product;
use App\Exception\IncorrectProductDateSoldAndScheduleStrategyUsageLogicException;
use App\Service\PaymentRules\JunePaymentScheduleStrategy;
use App\Tests\Common\AssertObject\PaymentScheduleAssertObject;
use App\Tests\Common\TestCase\UnitTestCase;
use DateTime;
use DateTimeInterface;

final class JunePaymentScheduleStrategyTest extends UnitTestCase
{
    private JunePaymentScheduleStrategy $strategy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->strategy = new JunePaymentScheduleStrategy();
    }

    /**
     * @dataProvider correctProductDataProvider
     */
    public function testItDoesCalculatePaymentSchedule(
        int $amount,
        int $expectedFirstInstalmentAmount,
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
            ->hasInstalmentsNumberEqualTo(2);
    }

    public function testItThrowsExceptionWhenDateSoldIsWrong(): void
    {
        $product = $this->createMock(Product::class);
        $dateSold = new DateTime('2024-11-01');

        $this->expectException(IncorrectProductDateSoldAndScheduleStrategyUsageLogicException::class);
        $this->strategy->generateSchedule($product, $dateSold);
    }

    public function correctProductDataProvider(): array
    {
        return [
            [1000, 300, 700, new DateTime('2024-06-01')],
            [1001, 300, 701, new DateTime('2024-06-01')],
        ];
    }
}
