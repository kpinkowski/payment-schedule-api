<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;

final class AmountCannotBeNegativeException extends Exception
{
    private const MESSAGE = 'Amount cannot be negative';

    public function __construct()
    {
        parent::__construct(self::MESSAGE, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
