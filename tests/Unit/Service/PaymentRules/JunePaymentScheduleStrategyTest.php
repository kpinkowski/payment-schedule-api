<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\PaymentRules;

use App\Entity\Money;
use App\Entity\Product;
use App\Enum\Currency;
use App\Exception\IncorrectProductDateSoldAndScheduleStrategyUsageLogicException;
use App\Service\PaymentRules\JunePaymentScheduleStrategy;
use App\Tests\Common\AssertObject\PaymentScheduleAssertObject;
use App\Tests\Common\TestCase\UnitTestCase;
use DateTimeImmutable;

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
        DateTimeImmutable $dateSold
    ): void {
        $money = new Money($amount, Currency::USD->value);
        $product = $this->createMock(Product::class);
        $product->method('getPrice')->willReturn($money);
        $product->method('getDateSold')->willReturn($dateSold);

        $schedule = $this->strategy->generateSchedule($product);

        PaymentScheduleAssertObject::assertThat($schedule)
            ->hasProduct($product)
            ->hasInstalmentsNumberEqualTo(2);
    }

    public function testItThrowsExceptionWhenDateSoldIsWrong(): void
    {
        $dateSold = new DateTimeImmutable('2024-11-01');
        $product = $this->createMock(Product::class);
        $product->method('getDateSold')->willReturn($dateSold);

        $this->expectException(IncorrectProductDateSoldAndScheduleStrategyUsageLogicException::class);
        $this->strategy->generateSchedule($product);
    }

    public function correctProductDataProvider(): array
    {
        return [
            [1000, 300, 700, new DateTimeImmutable('2024-06-01')],
            [1001, 300, 701, new DateTimeImmutable('2024-06-01')],
        ];
    }
}
