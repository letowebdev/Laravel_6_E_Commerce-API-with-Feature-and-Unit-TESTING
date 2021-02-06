<?php

namespace Tests\Feature\PaymentMethods;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PaymentMethodIndexTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_fails_to_load_payment_methods_if_not_authenticated()
    {
        $this->json('GET', 'api/paymentmethods')
             ->assertStatus(401);
    }

    public function test_it_returns_a_collection_of_payment_methods()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $user->payment_methods()->save(
            $payment_method = factory(PaymentMethod::class)->create()
        );

        $this->json('GET', 'api/paymentmethods')
             ->assertJsonFragment([
                 'id' => $payment_method->id,
                 'cart_type' => $payment_method->cart_type
             ]);
    }
}
