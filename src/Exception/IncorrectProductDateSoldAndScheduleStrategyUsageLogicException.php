<?php

declare(strict_types=1);

namespace App\Exception;

use LogicException;
use Symfony\Component\HttpFoundation\Response;

final class IncorrectProductDateSoldAndScheduleStrategyUsageLogicException extends LogicException
{
    private const MESSAGE = 'The product date sold does not match the schedule strategy usage.';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
