<?php

declare(strict_types=1);

namespace App\Tests\Integration\Messenger\Handler;

use App\Entity\Money;
use App\Enum\Currency;
use App\Enum\ProductType;
use App\Messenger\Command\CalculatePaymentScheduleCommand;
use App\Messenger\Command\Handler\CalculatePaymentScheduleHandler;
use App\Repository\PaymentScheduleRepository;
use App\Repository\ProductRepository;
use App\Tests\Common\AssertObject\PaymentScheduleAssertObject;
use App\Tests\Common\TestCase\IntegrationTestCase;
use App\Tests\DataFixtures\ProductFixtures;
use DateTimeImmutable;
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

    /**
     * @dataProvider productDataProvider
     */
    public function testItDoesGenerateDefaultPaymentSchedule(
        string $productName,
        string $dateSold,
        ProductType $productType,
        Money $productPrice
    ): void {
        Assert::assertCount(0, $this->paymentScheduleRepository->findAll());

        $command = new CalculatePaymentScheduleCommand(
            $productType,
            $productName,
            $productPrice,
            new DateTimeImmutable($dateSold)
        );

        $this->handler->__invoke($command);

        $schedules = $this->paymentScheduleRepository->findAll();

        Assert::assertCount(1, $schedules);
        $schedule = $schedules[0];

        PaymentScheduleAssertObject::assertThat($schedule)
            ->hasInstalmentsNumberEqualTo(1)
            ->installmentIsEqualTo(0, $productPrice->getAmount());
    }

    public function productDataProvider(): array
    {
        return [
            ['someProduct', '2024-05-01', ProductType::ELECTRONICS, new Money(1000, Currency::USD)],
        ];
    }
}
