<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MeTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_fails_if_user_is_not_authenticated()
    {
        $this->json('GET', 'api/auth/me')
            ->assertStatus(401);
    }

    public function test_it_returns_user_details()
    {
        $user = factory(User::class)->create();

        $this->jsonAs($user, 'GET', 'api/auth/me')
            ->assertJsonFragment([
                'name' => $user->name,
                'email' => $user->email
            ]);
    }
}
