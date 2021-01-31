<?php

namespace Tests\Feature\Countries;

use App\Models\Country;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CountryIndexTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_fails_to_load_countries_if_not_authenticated()
    {
        $this->json('GET', 'api/countries')
             ->assertStatus(401);
    }

    public function test_it_returns_a_list_of_countries_to_an_authenticated_user()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $country = factory(Country::class)->create();

        $this->json('GET', 'api/countries')
             ->assertJsonFragment([
                'code' => $country->code
             ]);
    }
}
