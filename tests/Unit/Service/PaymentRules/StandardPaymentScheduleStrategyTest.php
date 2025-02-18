<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\PaymentRules;

use App\Entity\Money;
use App\Entity\Product;
use App\Enum\Currency;
use App\Service\PaymentRules\StandardPaymentScheduleStrategy;
use App\Tests\Common\AssertObject\PaymentScheduleAssertObject;
use App\Tests\Common\TestCase\UnitTestCase;
use DateTimeImmutable;

final class StandardPaymentScheduleStrategyTest extends UnitTestCase
{
    private StandardPaymentScheduleStrategy $strategy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->strategy = new StandardPaymentScheduleStrategy();
    }

    /**
     * @dataProvider correctProductDataProvider
     */
    public function testItDoesCalculatePaymentSchedule(int $amount, DateTimeImmutable $dateSold): void
    {
        $money = new Money($amount, Currency::USD->value);
        $product = $this->createMock(Product::class);
        $product->method('getPrice')->willReturn($money);
        $product->method('getDateSold')->willReturn($dateSold);

        $schedule = $this->strategy->generateSchedule($product);

        PaymentScheduleAssertObject::assertThat($schedule)
            ->hasProduct($product)
            ->hasInstalmentsNumberEqualTo(1)
            ->installmentIsEqualTo(0, $amount);
    }

    public function correctProductDataProvider(): array
    {
        return [
            [1000, new DateTimeImmutable('2024-05-01')],
            [2000, new DateTimeImmutable('2024-03-01')],
            [4000, new DateTimeImmutable('2024-02-01')],
        ];
    }
}
