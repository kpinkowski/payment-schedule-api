<?php

declare(strict_types=1);

namespace App\Enum;

enum ProductType: string
{
    case ELECTRONICS = 'electronics';
    case FURNITURE = 'furniture';
    case SUBSCRIPTION = 'subscription';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
