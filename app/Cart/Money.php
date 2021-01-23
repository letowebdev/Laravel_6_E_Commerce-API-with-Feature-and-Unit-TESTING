<?php

namespace App\Cart;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money as BaseMoney;
use NumberFormatter;

class Money
{
    protected $money;

    public function __construct($value)
    {
        $this->money = new BaseMoney($value, new Currency('EUR'));    
    }

    public function formatted()
    {
        $formatter = new IntlMoneyFormatter(
            new NumberFormatter('de_DE', NumberFormatter::CURRENCY),
            new ISOCurrencies(),  
        );

        return $formatter->format($this->money);
    }

    public function amount()
    {
        return $this->money->getAmount();
    }
}