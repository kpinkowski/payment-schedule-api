<?php

declare(strict_types=1);

namespace App\Enum;

enum ProductType: string
{
    case ELECTRONICS = 'electronics';
    case FURNITURE = 'furniture';
    case CARS = 'cars';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
