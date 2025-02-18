<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Money;
use App\Enum\Currency;
use App\Service\CurrencyConverter;
use App\Tests\Common\TestCase\UnitTestCase;
use PHPUnit\Framework\Assert;

final class CurrencyConverterTest extends UnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->currencyConverter = new CurrencyConverter();
    }

    /**
     * @dataProvider currencyConversionDataProvider
     */
    public function testItDoesConvertCurrenciesCorrectly(
        Money $from,
        Money $expected
    ): void {
        $convertedAmount = $this->currencyConverter->convert($from, $expected->getCurrency());

        Assert::assertEquals($expected, $convertedAmount);
    }

    public function currencyConversionDataProvider(): array
    {
        return [
            [new Money(100, Currency::USD->value), new Money(100, Currency::USD->value)],
            [new Money(100, Currency::USD->value), new Money(85, Currency::EUR->value)],
            [new Money(100, Currency::USD->value), new Money(300, Currency::PLN->value)],
            [new Money(100, Currency::EUR->value), new Money(118, Currency::USD->value)],
            [new Money(100, Currency::EUR->value), new Money(100, Currency::EUR->value)],
            [new Money(100, Currency::EUR->value), new Money(353, Currency::PLN->value)],
            [new Money(100, Currency::PLN->value), new Money(33, Currency::USD->value)],
            [new Money(100, Currency::PLN->value), new Money(28, Currency::EUR->value)],
            [new Money(100, Currency::PLN->value), new Money(100, Currency::PLN->value)],
        ];
    }
}
