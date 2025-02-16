<?php

namespace App\Tests\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public const BASIC_PRODUCT = 'Basic product';

    public function getDependencies(): array
    {
        return [
            ProductTypeFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $manager->persist($this->createBasicProduct());
        $manager->flush();
    }

    private function createBasicProduct(): Product
    {
        $productType = $this->getReference(ProductTypeFixtures::PRODUCT_TYPE_1, ProductType::class);

        return $this->createProduct(self::BASIC_PRODUCT, 100, $productType, 'EUR');
    }

    private function createProduct(string $name, int $price, ProductType $productType, string $currency): Product
    {
        $product = new Product();

        $product->setName($name);
        $product->setProductType($productType);
        $product->setPrice($price);
        $product->setCurrency($currency);

        return $product;
    }
}
