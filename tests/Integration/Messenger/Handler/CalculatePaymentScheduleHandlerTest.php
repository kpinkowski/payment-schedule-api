<?php

declare(strict_types=1);

namespace App\Tests\Integration\Messenger\Handler;

use App\Messenger\Command\CalculatePaymentScheduleCommand;
use App\Messenger\Command\Handler\CalculatePaymentScheduleHandler;
use App\Repository\PaymentScheduleRepository;
use App\Repository\ProductRepository;
use App\Tests\Common\AssertObject\PaymentScheduleAssertObject;
use App\Tests\Common\TestCase\IntegrationTestCase;
use App\Tests\DataFixtures\ProductFixtures;
use PHPUnit\Framework\Assert;

final class CalculatePaymentScheduleHandlerTest extends IntegrationTestCase
{
    private CalculatePaymentScheduleHandler $handler;
    private ProductRepository $productRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->handler = $this->getService(CalculatePaymentScheduleHandler::class);
        $this->productRepository = $this->getService(ProductRepository::class);
        $this->paymentScheduleRepository = $this->getService(PaymentScheduleRepository::class);
    }

    public function testItDoesGenerateDefaultPaymentSchedule(): void
    {
        $dateSold = '2024-05-01';
        $product = $this->productRepository->getOneByName(ProductFixtures::BASIC_PRODUCT);

        Assert::assertNotNull($product);
        Assert::assertCount(0, $this->paymentScheduleRepository->getSchedulesByProduct($product));

        $command = new CalculatePaymentScheduleCommand($product, $dateSold);
        $this->handler->__invoke($command);

        $schedules = $this->paymentScheduleRepository->getSchedulesByProduct($product);

        Assert::assertCount(1, $schedules);
        $schedule = $schedules[0];

        PaymentScheduleAssertObject::assertThat($schedule)
            ->hasProduct($product)
            ->hasSameTotalAmountAsProduct($product)
            ->hasInstalmentsNumberEqualTo(1)
            ->installmentIsEqualTo(0, $product->getPrice()->getAmount());
    }
}
