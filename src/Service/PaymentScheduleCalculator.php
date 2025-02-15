<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use App\Service\PaymentRules\PaymentScheduleStrategyInterface;
use Doctrine\ORM\EntityManagerInterface;

final class PaymentScheduleCalculator
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PaymentScheduleStrategyInterface $strategy
    ) {
    }

    public function calculate(Product $product): void
    {
        $schedules = $this->strategy->generateSchedule($product);

        foreach ($schedules as $schedule) {
            $this->entityManager->persist($schedule);
        }

        $this->entityManager->flush();
    }
}
