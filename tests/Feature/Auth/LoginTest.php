<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_requires_an_email()
    {
        $this->json('POST', 'api/auth/login')
             ->assertJsonMissingValidationErrors([
                 'email'
             ]);
    }

    public function test_it_requires_a_valid_email()
    {
        $this->json('POST', 'api/auth/login' , [
            'email' => 'not_an_email'
        ])->assertJsonMissingValidationErrors([
                 'email'
             ]);
    }

    public function test_it_requires_a_password()
    {
        $this->json('POST', 'api/auth/login')
             ->assertJsonMissingValidationErrors([
                 'password'
             ]);
    }

    public function test_it_checks_if_the_credentials_do_not_match()
    {
        $user = factory(User::class)->create();
        $this->json('POST', 'api/auth/login' , [
             'email' => $email = 'random@email.com',
             'password' => $password = 'dummy_password'
        ]);

        $this->assertNotEquals($user->email, $email);
        $this->assertNotEquals($user->password, $password);
    }

    public function test_it_returns_a_token_if_authenticated()
    {
        $user = factory(User::class)->create();
        $this->json('POST', 'api/auth/login', [
            'email' => $user->email,
            'password' => 'my_cat'
        ])
        ->assertJsonStructure([
            'meta' => [
                'token'
            ]
        ]);
    }

    public function test_it_returns_a_user_data()
    {
        $user = factory(User::class)->create();
        $this->json('POST', 'api/auth/login', [
            'email' => $user->email,
            'password' => 'my_cat'
        ])
        ->assertJsonFragment([
            'name' => $user->name,
            'email' => $user->email
        ]);
    }


}
