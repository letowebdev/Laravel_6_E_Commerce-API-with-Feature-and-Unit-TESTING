<?php

namespace Tests\Unit\Resources\PaymentMethods;

use App\Http\Resources\PaymentMethodResource;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Testing\Assert;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PaymentMethodResourceTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_returns_correct_payment_method_resource_data() :void
    {
        $resource = (new PaymentMethodResource(
            $paymentMethod = factory(PaymentMethod::class)->create()
        ))->jsonSerialize();

        /**
         * Since assertArraySubset was deprecate in phpunit 8 and removed in PHPUnit 9
         * and no alternative was provided since then this was the only solution
         * provided by:
         * (https://github.com/rdohms/phpunit-arraysubset-asserts)
         */
        Assert::assertArraySubset([
            'id' => $paymentMethod->id,
            'cart_type' => $paymentMethod->cart_type,
            'last_four' => $paymentMethod->last_four,
            'default' => $paymentMethod->default,
            'id' => $paymentMethod->id,
        ], $resource);
    }
}
