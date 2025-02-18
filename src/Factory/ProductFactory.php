<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Money;
use App\Entity\Product;
use DateTimeImmutable;
use App\Enum\ProductType;

final class ProductFactory
{
    public function create(
        string $productName,
        DateTimeImmutable $dateSold,
        Money $productPrice,
        ProductType $productType,
    ): Product {
        return new Product(
            $productName,
            $dateSold,
            $productPrice,
            $productType
        );
    }
}
