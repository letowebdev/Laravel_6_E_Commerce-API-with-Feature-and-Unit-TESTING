<?php

namespace Tests\Unit\Models\PaymentMethods;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_belongs_to_one_user()
    {
        $payment_method = factory(PaymentMethod::class)->create();

        $this->assertInstanceOf(User::class, $payment_method->user);
    }

    public function test_it_turns_the_old_payment_method_to_false_if_the_new_one_is_to_be_set_true()
    {
        
        $user = factory(User::class)->create();

        $old_payment_method = factory(PaymentMethod::class)->create([
            'default' => true,
            'user_id' => $user->id
        ]);

        $new_payment_method = factory(PaymentMethod::class)->create([
            'default' => true,
            'user_id' => $user->id
        ]);
        
        /**
         * Since the default is set to boolean [0 == false && 1 == true]
         */
        $this->assertEquals(0, $old_payment_method->fresh()->default);
        $this->assertEquals(1, $new_payment_method->fresh()->default);
    }

}
