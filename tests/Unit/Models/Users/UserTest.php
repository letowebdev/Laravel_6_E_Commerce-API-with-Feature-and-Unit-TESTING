<?php

namespace Tests\Unit\Models\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function test_it_hashes_the_password_when_created()
    {
        $user = factory(User::class)->create([
            'password' => 'my_cat'
         ]);

         $this->assertNotEquals($user->password, 'my_cat');

    }
}
