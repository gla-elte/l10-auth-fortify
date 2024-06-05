<?php

namespace Tests\Feature;

use Tests\TestCase;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
  use RefreshDatabase;

  public function test_new_users_can_register(): void
  {
    if (!Features::enabled(Features::registration())) {
      $this->markTestSkipped('Registration support is not enabled.');
    }

    $response = $this->post('/register', [
      'name' => 'Test User',
      'email' => 'test@example.com',
      'password' => 'password',
      'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::HOME);
  }

  public function test_registration_requires_a_password_confirmation(): void
  {
    if (!Features::enabled(Features::registration())) {
      $this->markTestSkipped('Registration support is not enabled.');
    }

    $response = $this->post('/register', [
      'name' => 'Test User 2',
      'email' => 'test2@example.com',
      'password' => 'password',
    ]);

    $response->assertSessionHasErrors(['password']);
  }

  public function test_registration_requires_name_email_and_password()
  {
    $response = $this->post('/register', [
      // Üres kérés a regisztrációhoz
    ]);

    $response->assertSessionHasErrors(['name', 'email', 'password']);
  }

  public function test_registration_requires_a_valid_email()
  {
    $response = $this->post('/register', [
      'name' => 'John Doe',
      'email' => 'invalid-email',
      'password' => 'password',
      'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors(['email']);
  }

  public function test_registration_fails_if_email_already_exists()
  {
    // Először létrehozunk egy felhasználót az adott e-mail címmel
    \App\Models\User::create([
      'name' => 'Existing User',
      'email' => 'existing@example.com',
      'password' => Hash::make('password'),
    ]);
    // Megpróbáljuk újra regisztrálni ugyanazzal az e-mail címmel
    $response = $this->post('/register', [
      'name' => 'New User',
      'email' => 'existing@example.com',
      'password' => 'password',
      'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors(['email']);
  }
}
