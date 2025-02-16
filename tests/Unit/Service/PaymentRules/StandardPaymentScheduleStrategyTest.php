<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\PaymentRules;

use App\Entity\Product;
use App\Service\PaymentRules\StandardPaymentScheduleStrategy;
use App\Tests\Common\AssertObject\PaymentScheduleAssertObject;
use App\Tests\Common\TestCase\UnitTestCase;
use DateTime;
use DateTimeInterface;

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
    public function testItDoesCalculatePaymentSchedule(int $amount, DateTimeInterface $dateSold): void
    {
        $product = $this->createMock(Product::class);
        $product->method('getPrice')->willReturn($amount);

        $schedule = $this->strategy->generateSchedule($product, $dateSold);

        PaymentScheduleAssertObject::assertThat($schedule)
            ->hasProduct($product)
            ->hasSameTotalAmountAsProduct($product)
            ->hasInstalmentsNumberEqualTo(1)
            ->installmentIsEqualTo(0, $amount);
    }

    public function correctProductDataProvider(): array
    {
        return [
            [1000, new DateTime('2024-05-01')],
            [2000, new DateTime('2024-03-01')],
            [4000, new DateTime('2024-02-01')],
        ];
    }
}
