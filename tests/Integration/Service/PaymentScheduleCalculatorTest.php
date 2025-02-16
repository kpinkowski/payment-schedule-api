<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service;

use App\Repository\PaymentScheduleRepository;
use App\Repository\ProductRepository;
use App\Service\PaymentScheduleCalculator;
use App\Tests\Common\IntegrationTestCase;
use App\Tests\DataFixtures\ProductFixtures;
use PHPUnit\Framework\Assert;

final class PaymentScheduleCalculatorTest extends IntegrationTestCase
{
    private PaymentScheduleCalculator $calculator;
    private ProductRepository $productRepository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->calculator = $this->getService(PaymentScheduleCalculator::class);
        $this->productRepository = $this->getService(ProductRepository::class);
        $this->paymentScheduleRepository = $this->getService(PaymentScheduleRepository::class);
    }

    public function testItDoesGenerateDefaultPaymentSchedule(): void
    {
        $product = $this->productRepository->getOneByName(ProductFixtures::BASIC_PRODUCT);

        Assert::assertNotNull($product);
        Assert::assertCount(0, $this->paymentScheduleRepository->getSchedulesByProduct($product));

        $this->calculator->calculate($product);

        $schedules = $this->paymentScheduleRepository->getSchedulesByProduct($product);

        Assert::assertCount(0, $schedules);
    }
}
