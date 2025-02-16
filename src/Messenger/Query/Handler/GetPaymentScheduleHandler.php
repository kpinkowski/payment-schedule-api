<?php

declare(strict_types=1);

namespace App\Messenger\Query\Handler;

use App\Messenger\Query\GetPaymentScheduleQuery;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus')]
final class GetPaymentScheduleHandler
{
    public function __invoke(GetPaymentScheduleQuery $query): void
    {
        // TODO: Implement __invoke() method.
    }
}
