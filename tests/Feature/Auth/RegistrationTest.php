<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_requires_a_name()
    {
        $this->json('POST', 'api/auth/register')
             ->assertJsonMissingValidationErrors(['name']);
    }

    public function test_it_requires_a_password()
    {
        $this->json('POST', 'api/auth/register')
             ->assertJsonMissingValidationErrors(['password']);
    }

    public function test_it_requires_a_email()
    {
        $this->json('POST', 'api/auth/register')
             ->assertJsonMissingValidationErrors(['email']);
    }

    public function test_it_requires_a_valid_email()
    {
        $this->json('POST', 'api/auth/register', [
            'email' => 'not_an_email'
        ])
             ->assertJsonMissingValidationErrors(['email']);
    }

    public function test_it_checks_if_email_already_exists()
    {
        $user = factory(User::class)->create();
        $this->json('POST', 'api/auth/register', [
            'email' => $user->email
        ])
             ->assertJsonMissingValidationErrors(['email']);
    }

    public function test_it_registers_a_user()
    {
        $this->json('POST', 'api/auth/register', [
            'name' => $name = 'leto',
            'email' => $email = 'test@it.de',
            'password' => 'secret'
        ]);
        
        $this->assertDatabaseHas('users', [
                'name' => $name, 
                'email' => $email
             ]);
    }

    public function test_it_returns_the_registred_user_data()
    {
        $this->json('POST', 'api/auth/register', [
            'name' => $name = 'leto',
            'email' => $email = 'test@it.de',
            'password' => 'secret'
        ])->assertJsonFragment([
            'name' => $name, 
            'email' => $email
        ]);
    }


}
