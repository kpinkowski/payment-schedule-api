<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\PaymentSchedule;
use App\Entity\Product;
use App\Service\PaymentRules\DecemberYearlyScheduleStrategy;
use App\Service\PaymentRules\JanuaryTwoEqualScheduleStrategy;
use App\Service\PaymentRules\JunePaymentScheduleStrategy;
use App\Service\PaymentRules\PaymentScheduleStrategyInterface;
use App\Service\PaymentRules\StandardPaymentScheduleStrategy;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final class PaymentScheduleCalculator
{
    private const LOG_TAG = '[PaymentScheduleCalculator]: ';
    private const GENERATING_LOG = self::LOG_TAG . 'Generating schedule...';
    private const GENERATED_LOG = self::LOG_TAG . 'Generated schedule.';
    private const ERROR_LOG = self::LOG_TAG . 'Error generating schedule.';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly StandardPaymentScheduleStrategy $standardPaymentScheduleStrategy,
        private readonly JanuaryTwoEqualScheduleStrategy $januaryTwoEqualScheduleStrategy,
        private readonly JunePaymentScheduleStrategy $junePaymentScheduleStrategy,
        private readonly DecemberYearlyScheduleStrategy $decemberYearlyScheduleStrategy,
        private readonly LoggerInterface $logger
    ) {
    }

    public function calculate(Product $product, DateTimeInterface $dateSold): PaymentSchedule
    {
        try {
            $this->entityManager->beginTransaction();

            $strategy = $this->getStrategy($dateSold);

            $this->logger->debug(self::GENERATING_LOG, [
                'productId' => $product->getId(),
                'dateSold' => $dateSold->format('Y-m-d'),
                'strategy' => get_class($strategy)
            ]);

            $schedule = $strategy->generateSchedule($product, $dateSold);

            $this->entityManager->persist($schedule);
            $this->entityManager->flush();
            $this->entityManager->commit();

            $this->logger->debug(self::GENERATED_LOG, [
                'productId' => $product->getId(),
                'dateSold' => $dateSold->format('Y-m-d'),
                'scheduleId' => $schedule->getId()
            ]);

            return $schedule;
        } catch (Throwable $e) {
            $this->entityManager->rollback();

            $this->logger->error(self::ERROR_LOG, [
                'productId' => $product->getId(),
                'dateSold' => $dateSold->format('Y-m-d'),
                'strategy' => (isset($strategy) ? get_class($strategy) : null),
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    private function getStrategy(DateTimeInterface $dateSold): PaymentScheduleStrategyInterface
    {
        if ($dateSold->format('m') === '01') {
            return $this->januaryTwoEqualScheduleStrategy;
        }

        if ($dateSold->format('m') === '06') {
            return $this->junePaymentScheduleStrategy;
        }

        if ($dateSold->format('m') === '12') {
            return $this->decemberYearlyScheduleStrategy;
        }

        return $this->standardPaymentScheduleStrategy;
    }
}
