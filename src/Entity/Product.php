<?php

namespace App\Entity;

use App\Enum\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $productType;

    #[ORM\Embedded(class: Money::class)]
    private Money $price;

    #[ORM\Column]
    private DateTimeImmutable $dateSold;

    public function __construct(
        string $name,
        DateTimeImmutable $dateSold,
        Money $price,
        ProductType $productType
    ) {
        $this->name = $name;
        $this->dateSold = $dateSold;
        $this->productType = $productType->value;
        $this->price = $price;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getProductType(): ProductType
    {
        return ProductType::from($this->productType);
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function getDateSold(): DateTimeImmutable
    {
        return $this->dateSold;
    }
}
