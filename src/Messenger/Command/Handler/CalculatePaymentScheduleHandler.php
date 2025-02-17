<?php

declare(strict_types=1);

namespace App\Messenger\Command\Handler;

use App\Messenger\Command\CalculatePaymentScheduleCommand;
use App\Service\PaymentScheduleCalculator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'command.bus')]
final class CalculatePaymentScheduleHandler
{
    public function __construct(private readonly PaymentScheduleCalculator $calculator)
    {
    }

    public function __invoke(CalculatePaymentScheduleCommand $command): void
    {
        $this->calculator->calculate(
            $command->getProduct(),
            $command->getDateSold()
        );
    }
}
