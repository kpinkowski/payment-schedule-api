<?php

namespace App\Entity;

use App\Repository\PaymentScheduleItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

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

    #[ORM\Embedded(class: Money::class)]
    private Money $amount;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?DateTimeImmutable $dueDate = null;

    public function __construct(Money $amount, DateTimeImmutable $dueDate)
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

    public function getDueDate(): ?DateTimeImmutable
    {
        return $this->dueDate;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }
}
