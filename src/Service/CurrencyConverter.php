<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Money;
use App\Enum\Currency;
use App\Exception\UnsupportedCurrencyException;

final class CurrencyConverter
{
    public function convert(Money $amountFrom, Currency $toCurrency): Money
    {
        if ($amountFrom->getCurrency() === $toCurrency) {
            return $amountFrom;
        }

        $fromCurrencyRate = $this->getCurrencyRate($amountFrom->getCurrency());
        $toCurrencyRate = $this->getCurrencyRate($toCurrency);

        $amountTo = (int) round($amountFrom->getAmount() * $toCurrencyRate / $fromCurrencyRate);

        return new Money($amountTo, $toCurrency->value);
    }

    private function getCurrencyRate(Currency $currency): int
    {
        return match ($currency) {
            Currency::USD => 100,
            Currency::EUR => 85,
            Currency::PLN => 300,
            default => throw new UnsupportedCurrencyException($currency->value),
        };
    }
}
