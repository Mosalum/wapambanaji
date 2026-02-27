<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_phone(): void
    {
        User::factory()->create(['phone' => '255700111222', 'password' => Hash::make('Password123!')]);

        $response = $this->post('/login', ['login' => '255700111222', 'password' => 'Password123!']);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }
}
