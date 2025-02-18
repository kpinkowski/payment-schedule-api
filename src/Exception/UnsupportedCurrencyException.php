<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

final class UnsupportedCurrencyException extends Exception
{
    public function __construct(string $currency)
    {
        parent::__construct("Unsupported currency: $currency", 500);
    }
}
