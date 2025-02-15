<?php

declare(strict_types=1);

namespace App\Handler\Query;

use App\Message\Query\GetPaymentScheduleQuery;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'query.bus')]
final class GetPaymentScheduleHandler
{
    public function __invoke(GetPaymentScheduleQuery $query): void
    {
        // TODO: Implement __invoke() method.
    }
}
