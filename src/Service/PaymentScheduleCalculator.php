<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\PaymentSchedule;
use App\Entity\Product;
use App\Enum\ProductType;
use App\Service\PaymentRules\DecemberYearlyScheduleStrategy;
use App\Service\PaymentRules\CarProductTypeTwoEqualScheduleStrategy;
use App\Service\PaymentRules\JunePaymentScheduleStrategy;
use App\Service\PaymentRules\PaymentScheduleStrategyInterface;
use App\Service\PaymentRules\StandardPaymentScheduleStrategy;
use Psr\Log\LoggerInterface;

final class PaymentScheduleCalculator
{
    private const LOG_TAG = '[PaymentScheduleCalculator]: ';
    private const GENERATING_LOG = self::LOG_TAG . 'Generating schedule...';
    private const GENERATED_LOG = self::LOG_TAG . 'Generated schedule.';

    public function __construct(
        private readonly StandardPaymentScheduleStrategy $standardPaymentScheduleStrategy,
        private readonly CarProductTypeTwoEqualScheduleStrategy $carProductTypeTwoEqualScheduleStrategy,
        private readonly JunePaymentScheduleStrategy $junePaymentScheduleStrategy,
        private readonly DecemberYearlyScheduleStrategy $decemberYearlyScheduleStrategy,
        private readonly LoggerInterface $appLogger
    ) {
    }

    public function calculate(Product $product): PaymentSchedule
    {
        $strategy = $this->getStrategy($product);

        $this->appLogger->info(self::GENERATING_LOG, [
            'productType' => $product->getProductType()->value,
            'productName' => $product->getName(),
            'productPriceAmount' => $product->getPrice()->getAmount(),
            'productPriceCurrency' => $product->getPrice()->getCurrency(),
            'dateSold' => $product->getDateSold()->format('Y-m-d'),
            'strategy' => get_class($strategy)
        ]);

        $schedule = $strategy->generateSchedule($product);

        $this->appLogger->info(self::GENERATED_LOG, [
            'productType' => $product->getProductType()->value,
            'productName' => $product->getName(),
            'productPriceAmount' => $product->getPrice()->getAmount(),
            'productPriceCurrency' => $product->getPrice()->getCurrency(),
            'dateSold' => $product->getDateSold()->format('Y-m-d'),
            'strategy' => get_class($strategy),
            'scheduleId' => $schedule->getId()
        ]);

        return $schedule;
    }

    private function getStrategy(Product $product): PaymentScheduleStrategyInterface
    {
        if ($product->getProductType() === ProductType::CARS) {
            return $this->carProductTypeTwoEqualScheduleStrategy;
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
