<?php

namespace Tests\Feature\Models\Addresses;

use App\Models\Address;
use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_has_one_country()
    {
        //The country id is already included in the address factory
        $address = factory(Address::class)->create();

        $this->assertInstanceOf(Country::class, $address->country);
    }

    public function test_it_belongs_to_a_user()
    {
        //The country id is already included in the address factory
        $address = factory(Address::class)->create();

        $this->assertInstanceOf(User::class, $address->user);
    }

    public function test_it_turns_the_old_address_to_false_if_the_new_one_is_to_be_set_true()
    {
        
        $user = factory(User::class)->create();

        $old_address = factory(Address::class)->create([
            'default' => true,
            'user_id' => $user->id
        ]);

        $new_address = factory(Address::class)->create([
            'default' => true,
            'user_id' => $user->id
        ]);
        
        /**
         * Since the default is set to boolean [0 == false && 1 == true]
         */
        $this->assertEquals(0, $old_address->fresh()->default);
        $this->assertEquals(1, $new_address->fresh()->default);
    }

}
