<?php

namespace App\Models\Traits;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use NumberFormatter;

trait HasPrice
{
    public function getPriceAttribute($value)
    {
        return new Money($value, new Currency('EUR'));
    }

    public function getFormattedPriceAttribute()
    {
        $formatter = new IntlMoneyFormatter(
            new NumberFormatter('de_EUR', NumberFormatter::CURRENCY),
            new ISOCurrencies(),  
        );

        return $formatter->format($this->price);
    }
}