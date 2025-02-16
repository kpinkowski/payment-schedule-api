<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures;

use App\Entity\ProductType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

final class ProductTypeFixtures extends AbstractFixture
{
    public const PRODUCT_TYPE_1 = 'Product type 1';

    public function load(ObjectManager $manager): void
    {
        $manager->persist($this->createProductType(self::PRODUCT_TYPE_1));

        $manager->flush();
    }

    private function createProductType(string $name): ProductType
    {
        $productType = new ProductType();
        $productType->setName($name);

        $this->addReference($name, $productType);

        return $productType;
    }
}
