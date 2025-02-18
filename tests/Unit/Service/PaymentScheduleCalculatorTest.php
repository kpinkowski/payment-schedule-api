<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Money;
use App\Entity\Product;
use App\Enum\Currency;
use App\Enum\ProductType;
use App\Service\PaymentRules\DecemberYearlyScheduleStrategy;
use App\Service\PaymentRules\JanuaryTwoEqualScheduleStrategy;
use App\Service\PaymentRules\JunePaymentScheduleStrategy;
use App\Service\PaymentRules\StandardPaymentScheduleStrategy;
use App\Service\PaymentScheduleCalculator;
use App\Tests\Common\TestCase\UnitTestCase;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;

final class PaymentScheduleCalculatorTest extends UnitTestCase
{
    private PaymentScheduleCalculator $calculator;
    private EntityManagerInterface|MockObject $entityManager;
    private StandardPaymentScheduleStrategy|MockObject $standardPaymentScheduleStrategy;
    private JanuaryTwoEqualScheduleStrategy|MockObject $januaryTwoEqualScheduleStrategy;
    private JunePaymentScheduleStrategy|MockObject $junePaymentScheduleStrategy;
    private DecemberYearlyScheduleStrategy|MockObject $decemberYearlyScheduleStrategy;
    private LoggerInterface|MockObject $logger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->standardPaymentScheduleStrategy = $this->createMock(StandardPaymentScheduleStrategy::class);
        $this->januaryTwoEqualScheduleStrategy = $this->createMock(JanuaryTwoEqualScheduleStrategy::class);
        $this->junePaymentScheduleStrategy = $this->createMock(JunePaymentScheduleStrategy::class);
        $this->decemberYearlyScheduleStrategy = $this->createMock(DecemberYearlyScheduleStrategy::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->calculator = new PaymentScheduleCalculator(
            $this->standardPaymentScheduleStrategy,
            $this->januaryTwoEqualScheduleStrategy,
            $this->junePaymentScheduleStrategy,
            $this->decemberYearlyScheduleStrategy,
            $this->logger
        );
    }

    public function testItDoesChooseJanuaryTwoEqualPaymentScheduleStrategy(): void
    {
        $dateSold = new DateTimeImmutable('2024-01-01');
        $product = $this->createMock(Product::class);
        $product->method('getDateSold')->willReturn($dateSold);
        $product->method('getPrice')->willReturn(new Money(1000, Currency::USD->value));
        $product->method('getProductType')->willReturn(ProductType::ELECTRONICS);

        $this->januaryTwoEqualScheduleStrategy->expects($this->once())
            ->method('generateSchedule')
            ->with($product);

        $this->calculator->calculate($product);
    }

    public function testItDoesChooseJunePaymentScheduleStrategy(): void
    {
        $dateSold = new DateTimeImmutable('2024-06-01');
        $product = $this->createMock(Product::class);
        $product->method('getDateSold')->willReturn($dateSold);
        $product->method('getPrice')->willReturn(new Money(1000, Currency::USD->value));
        $product->method('getProductType')->willReturn(ProductType::ELECTRONICS);

        $this->junePaymentScheduleStrategy->expects($this->once())
            ->method('generateSchedule')
            ->with($product);

        $this->calculator->calculate($product);
    }

    public function testItDoesChooseDecemberYearlyPaymentScheduleStrategy(): void
    {
        $dateSold = new DateTimeImmutable('2024-12-01');
        $product = $this->createMock(Product::class);
        $product->method('getDateSold')->willReturn($dateSold);
        $product->method('getPrice')->willReturn(new Money(1000, Currency::USD->value));
        $product->method('getProductType')->willReturn(ProductType::ELECTRONICS);

        $this->decemberYearlyScheduleStrategy->expects($this->once())
            ->method('generateSchedule')
            ->with($product);

        $this->calculator->calculate($product);
    }

    public function testItDoesChooseStandardPaymentScheduleStrategy(): void
    {
        $dateSold = new DateTimeImmutable('2024-05-01');
        $product = $this->createMock(Product::class);
        $product->method('getDateSold')->willReturn($dateSold);
        $product->method('getPrice')->willReturn(new Money(1000, Currency::USD->value));
        $product->method('getProductType')->willReturn(ProductType::ELECTRONICS);

        $this->standardPaymentScheduleStrategy->expects($this->once())
            ->method('generateSchedule')
            ->with($product);

        $this->calculator->calculate($product);
    }
}
