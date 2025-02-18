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

    /**
     * @var Collection<int, PaymentScheduleItem>
     */
    #[ORM\OneToMany(targetEntity: PaymentScheduleItem::class, mappedBy: 'paymentSchedule', cascade: ['persist'], orphanRemoval: true)]
    private Collection $paymentScheduleItems;

    #[ORM\OneToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    public function __construct()
    {
        $this->paymentScheduleItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }
}
