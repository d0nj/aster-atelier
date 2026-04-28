<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Nguyen Van A',
            'email' => 'A@Example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('orders.index'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'a@example.com',
            'name' => 'Nguyen Van A',
        ]);
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'member@example.com',
            'password' => 'password123',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'Member@Example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('orders.index'));
        $this->assertAuthenticatedAs($user);
    }
}
