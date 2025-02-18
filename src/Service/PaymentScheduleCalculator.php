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
use Psr\Log\LoggerInterface;

final class PaymentScheduleCalculator
{
    private const LOG_TAG = '[PaymentScheduleCalculator]: ';
    private const GENERATING_LOG = self::LOG_TAG . 'Generating schedule...';
    private const GENERATED_LOG = self::LOG_TAG . 'Generated schedule.';
    private const ERROR_LOG = self::LOG_TAG . 'Error generating schedule.';

    public function __construct(
        private readonly StandardPaymentScheduleStrategy $standardPaymentScheduleStrategy,
        private readonly JanuaryTwoEqualScheduleStrategy $januaryTwoEqualScheduleStrategy,
        private readonly JunePaymentScheduleStrategy $junePaymentScheduleStrategy,
        private readonly DecemberYearlyScheduleStrategy $decemberYearlyScheduleStrategy,
        private readonly LoggerInterface $logger
    ) {
    }

    public function calculate(Product $product): PaymentSchedule
    {
        $strategy = $this->getStrategy($product);

        $this->logger->debug(self::GENERATING_LOG, [
            'productType' => $product->getProductType()->value,
            'productName' => $product->getName(),
            'productPriceAmount' => $product->getPrice()->getAmount(),
            'productPriceCurrency' => $product->getPrice()->getCurrency(),
            'dateSold' => $product->getDateSold()->format('Y-m-d'),
            'strategy' => get_class($strategy)
        ]);

        $schedule = $strategy->generateSchedule($product);

        $this->logger->debug(self::GENERATED_LOG, [
            'productType' => $product->getProductType()->value,
            'productName' => $product->getName(),
            'productPriceAmount' => $product->getPrice()->getAmount(),
            'productPriceCurrency' => $product->getPrice()->getCurrency(),
            'dateSold' => $product->getDateSold()->format('Y-m-d'),
            'scheduleId' => $schedule->getId()
        ]);

        return $schedule;
    }

    private function getStrategy(Product $product): PaymentScheduleStrategyInterface
    {
        if ($product->getDateSold()->format('m') === '01') {
            return $this->januaryTwoEqualScheduleStrategy;
        }

        if ($product->getDateSold()->format('m') === '06') {
            return $this->junePaymentScheduleStrategy;
        }

        if ($product->getDateSold()->format('m') === '12') {
            return $this->decemberYearlyScheduleStrategy;
        }

        return $this->standardPaymentScheduleStrategy;
    }
}
