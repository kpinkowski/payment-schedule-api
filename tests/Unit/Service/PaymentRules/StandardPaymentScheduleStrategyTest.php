<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\PaymentRules;

use App\Entity\Product;
use App\Service\PaymentRules\StandardPaymentScheduleStrategy;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

final class StandardPaymentScheduleStrategyTest extends TestCase
{
    protected function setUp(): void
    {
        $this->strategy = new StandardPaymentScheduleStrategy();
    }

    public function testItDoesCalculatePaymentSchedule(): void
    {
        $product = $this->createMock(Product::class);

        $schedules = $this->strategy->generateSchedule($product);

        Assert::assertIsArray($schedules);
    }
}
