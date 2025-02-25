<?php

declare(strict_types=1);

namespace App\Enum;

enum Currency : string
{
    case PLN = 'PLN';
    case USD = 'USD';
    case EUR = 'EUR';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
