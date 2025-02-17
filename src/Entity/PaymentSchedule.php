<?php

namespace App\Entity;

use App\Repository\PaymentScheduleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentScheduleRepository::class)]
class PaymentSchedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    /**
     * @var Collection<int, PaymentScheduleItem>
     */
    #[ORM\OneToMany(targetEntity: PaymentScheduleItem::class, mappedBy: 'paymentSchedule', cascade: ['persist'], orphanRemoval: true)]
    private Collection $paymentScheduleItems;

    #[ORM\Embedded(class: Money::class)]
    private Money $totalAmount;

    public function __construct()
    {
        $this->paymentScheduleItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): static
    {
        $this->product = $product;
        $this->totalAmount = clone $product->getPrice();

        return $this;
    }

    /**
     * @return Collection<int, PaymentScheduleItem>
     */
    public function getPaymentScheduleItems(): Collection
    {
        return $this->paymentScheduleItems;
    }

    public function addPaymentScheduleItem(PaymentScheduleItem $paymentScheduleItem): static
    {
        if (!$this->paymentScheduleItems->contains($paymentScheduleItem)) {
            $this->paymentScheduleItems->add($paymentScheduleItem);
            $paymentScheduleItem->setPaymentSchedule($this);
        }

        return $this;
    }

    public function removePaymentScheduleItem(PaymentScheduleItem $paymentScheduleItem): static
    {
        if ($this->paymentScheduleItems->removeElement($paymentScheduleItem)) {
            // set the owning side to null (unless already changed)
            if ($paymentScheduleItem->getPaymentSchedule() === $this) {
                $paymentScheduleItem->setPaymentSchedule(null);
            }
        }

        return $this;
    }

    public function getTotalAmount(): Money
    {
        return $this->totalAmount;
    }
}
