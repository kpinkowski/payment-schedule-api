<?php

namespace App\Entity;

use App\Repository\PaymentScheduleItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

#[ORM\Entity(repositoryClass: PaymentScheduleItemRepository::class)]
class PaymentScheduleItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'paymentScheduleItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PaymentSchedule $paymentSchedule = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?DateTimeInterface $dueDate = null;

    public function __construct(int $amount, DateTimeInterface $dueDate)
    {
        $this->amount = $amount;
        $this->dueDate = $dueDate;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentSchedule(): ?PaymentSchedule
    {
        return $this->paymentSchedule;
    }

    public function setPaymentSchedule(?PaymentSchedule $paymentSchedule): static
    {
        $this->paymentSchedule = $paymentSchedule;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        // TODO: Add validation for amount
        $this->amount = $amount;

        return $this;
    }

    public function getDueDate(): ?DateTimeInterface
    {
        return $this->dueDate;
    }

    public function setDueDate(DateTimeInterface $dueDate): static
    {
        $this->dueDate = $dueDate;

        return $this;
    }
}
