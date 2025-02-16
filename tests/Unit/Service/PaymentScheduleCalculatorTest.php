<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Product;
use App\Service\PaymentRules\DecemberYearlyScheduleStrategy;
use App\Service\PaymentRules\JanuaryTwoEqualScheduleStrategy;
use App\Service\PaymentRules\JunePaymentScheduleStrategy;
use App\Service\PaymentRules\StandardPaymentScheduleStrategy;
use App\Service\PaymentScheduleCalculator;
use App\Tests\Common\TestCase\UnitTestCase;
use DateTime;
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

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->standardPaymentScheduleStrategy = $this->createMock(StandardPaymentScheduleStrategy::class);
        $this->januaryTwoEqualScheduleStrategy = $this->createMock(JanuaryTwoEqualScheduleStrategy::class);
        $this->junePaymentScheduleStrategy = $this->createMock(JunePaymentScheduleStrategy::class);
        $this->decemberYearlyScheduleStrategy = $this->createMock(DecemberYearlyScheduleStrategy::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->calculator = new PaymentScheduleCalculator(
            $this->entityManager,
            $this->standardPaymentScheduleStrategy,
            $this->januaryTwoEqualScheduleStrategy,
            $this->junePaymentScheduleStrategy,
            $this->decemberYearlyScheduleStrategy,
            $this->logger
        );
    }

    public function testItDoesChooseJanuaryTwoEqualPaymentScheduleStrategy(): void
    {
        $product = $this->createMock(Product::class);
        $dateSold = new DateTime('2024-01-01');

        $this->januaryTwoEqualScheduleStrategy->expects($this->once())
            ->method('generateSchedule')
            ->with($product, $dateSold);

        $this->calculator->calculate($product, $dateSold);
    }

    public function testItDoesChooseJunePaymentScheduleStrategy(): void
    {
        $product = $this->createMock(Product::class);
        $dateSold = new DateTime('2024-06-01');

        $this->junePaymentScheduleStrategy->expects($this->once())
            ->method('generateSchedule')
            ->with($product, $dateSold);

        $this->calculator->calculate($product, $dateSold);
    }

    public function testItDoesChooseDecemberYearlyPaymentScheduleStrategy(): void
    {
        $product = $this->createMock(Product::class);
        $dateSold = new DateTime('2024-12-01');

        $this->decemberYearlyScheduleStrategy->expects($this->once())
            ->method('generateSchedule')
            ->with($product, $dateSold);

        $this->calculator->calculate($product, $dateSold);
    }

    public function testItDoesChooseStandardPaymentScheduleStrategy(): void
    {
        $product = $this->createMock(Product::class);
        $dateSold = new DateTime('2024-05-01');

        $this->standardPaymentScheduleStrategy->expects($this->once())
            ->method('generateSchedule')
            ->with($product, $dateSold);

        $this->calculator->calculate($product, $dateSold);
    }
}
