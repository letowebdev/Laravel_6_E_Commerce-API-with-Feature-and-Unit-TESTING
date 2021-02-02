<?php

namespace Tests\Unit\Money;

use App\Cart\Money;
use Money\Money as BaseMoney;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MoneyTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_can_get_the_raw_amount()
    {
        $money = new Money(1000);

        $this->assertEquals($money->amount(), 1000);
    }

    public function test_it_can_get_the_formatted_amount()
    {
        $money = new Money(1000);

        $this->assertEquals(preg_replace('/\xc2\xa0/', ' ', $money->formatted()), '10,00 €');
    }

    public function test_it_can_get_add()
    {
        $money = new Money(1000);

        $money = $money->add($money);

        $this->assertEquals($money->amount(), 2000);
    }

    public function test_it_can_return_the_underlying_instance()
    {
        $money = new Money(1000);

        $this->assertInstanceOf(BaseMoney::class, $money->instance());
    }


}
