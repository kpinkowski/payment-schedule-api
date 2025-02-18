<?php

declare(strict_types=1);

namespace App\Messenger\Command\Handler;

use App\Entity\PaymentSchedule;
use App\Factory\ProductFactory;
use App\Messenger\Command\CalculatePaymentScheduleCommand;
use App\Service\PaymentScheduleCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

#[AsMessageHandler(bus: 'message.bus')]
final class CalculatePaymentScheduleHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PaymentScheduleCalculator $calculator,
        private readonly ProductFactory $productFactory
    ) {
    }

    public function __invoke(CalculatePaymentScheduleCommand $command): PaymentSchedule
    {
        try {
            $this->entityManager->beginTransaction();

            $product = $this->productFactory->create(
                $command->getProductName(),
                $command->getDateSold(),
                $command->getProductPrice(),
                $command->getProductType()
            );

            $this->entityManager->persist($product);

            $schedule = $this->calculator->calculate($product);

            $this->entityManager->persist($schedule);

            $this->entityManager->flush();
            $this->entityManager->commit();

            return $schedule;
        } catch (Throwable $e) {
            $this->entityManager->rollback();
            throw $e;
        }
    }
}
