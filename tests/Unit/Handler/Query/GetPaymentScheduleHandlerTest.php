<?php

declare(strict_types=1);

namespace App\Tests\Unit\Handler\Query;

use App\Handler\Query\GetPaymentScheduleHandler;
use App\Message\Query\GetPaymentScheduleQuery;
use DG\BypassFinals;
use PHPUnit\Framework\TestCase;

final class GetPaymentScheduleHandlerTest extends TestCase
{
    private GetPaymentScheduleHandler $handler;

    protected function setUp(): void
    {
        // TODO: Create base unit tase case
        BypassFinals::enable();

        $this->handler = new GetPaymentScheduleHandler();

        parent::setUp();
    }

    public function testItDoesGetPaymentSchedule(): void
    {
        $query = $this->createMock(GetPaymentScheduleQuery::class);

        $this->handler->__invoke($query);

        // TODO: Implement test
    }
}
