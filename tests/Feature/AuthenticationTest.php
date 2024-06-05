<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
  use RefreshDatabase;

  public function test_users_can_authenticate(): void
  {
    $user = User::factory()->create();

    $response = $this->post('/login', [
      'email' => $user->email,
      'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::HOME);
  }

  public function test_users_can_not_authenticate_with_invalid_password(): void
  {
    $user = User::factory()->create();

    $this->post('/login', [
      'email' => $user->email,
      'password' => 'wrong-password',
    ]);

    $this->assertGuest();
  }

  public function guest_cannot_access_protected_route()
  {
    $response = $this->get('/api/user');

    $response->assertRedirect('/login');
  }

  public function test_authenticated_user_can_access_protected_route()
  {
    // Létrehozunk egy teszt felhasználót
    $user = User::factory()->create();

    // Szimuláljuk a bejelentkezést
    $this->actingAs($user);

    $response = $this->get('/api/user');

    $response->assertStatus(200);
  }

  public function test_authenticated_user_can_logout()
  {
    // Létrehozunk egy teszt felhasználót
    $user = User::factory()->create();

    // Szimuláljuk a bejelentkezést
    $this->actingAs($user);

    // Szimuláljuk a kijelentkezést
    $response = $this->post('/logout');

    // Ellenőrizzük, hogy a felhasználó átirányításra került a főoldalra (vagy esetleg a bejelentkezési oldalra)
    $response->assertRedirect('/');

    // Ellenőrizzük, hogy a felhasználó kijelentkezett
    $this->assertGuest();
  }
}
