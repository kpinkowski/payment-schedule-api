<?php

declare(strict_types=1);

namespace App\Tests\Unit\Handler\Command;

use App\Handler\Command\CalculatePaymentScheduleHandler;
use App\Message\Command\CalculatePaymentScheduleCommand;
use App\Service\PaymentScheduleCalculator;
use DG\BypassFinals;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CalculatePaymentScheduleHandlerTest extends TestCase
{
    private PaymentScheduleCalculator|MockObject $calculator;
    private CalculatePaymentScheduleHandler $handler;

    protected function setUp(): void
    {
        BypassFinals::enable();

        $this->calculator = $this->createMock(PaymentScheduleCalculator::class);
        $this->handler = new CalculatePaymentScheduleHandler($this->calculator);
    }

    public function testItDoesHandleCalculatePaymentScheduleCommand(): void
    {
        $command = $this->createMock(CalculatePaymentScheduleCommand::class);

        $this->calculator
            ->expects(self::once())
            ->method('calculate')
            ->with($command->getProduct());

        $this->handler->__invoke($command);
    }
}
