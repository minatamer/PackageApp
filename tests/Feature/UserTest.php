<?php

namespace Tests\Feature;

use App\Models\{User};
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Socialite;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_guests_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ]);
        $response->assertStatus(302); 
        $this->assertAuthenticated(); 
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ]);
    }

    public function test_forgot_password_screen_can_be_rendered(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_forgot_password_request_sends_link()
    {
        $user = User::create([
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);
        $response = $this->post('/forgot-password', [
            'email' => 'user@example.com', 
        ]);
        $response->assertStatus(302); 
        $response->assertSessionHas('status', 'Mail sent!'); 
        $response->assertRedirect('/');

    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::create([
            'email' => 'user@example.com',
            'password' => Hash::make('password'), 
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::create([
            'email' => 'user@example.com',
            'password' => Hash::make('password'),  
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_not_authenticate_with_empty_password(): void
    {
        $user = User::create([
            'email' => 'user@example.com',
            'password' => Hash::make('password'),  
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
        ]);

        $this->assertGuest();
    }

    public function test_users_can_not_authenticate_with_empty_email(): void
    {
        $user = User::create([
            'email' => 'user@example.com',
            'password' => Hash::make('password'), 
        ]);

        $response = $this->post('/login', [
            'password' => 'password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_not_authenticate_with_wrong_email_format(): void
    {
        $user = User::create([
            'email' => 'user@example.com',
            'password' => Hash::make('password'),  
        ]);
        $modifiedEmail = strstr($user->email, '@', true);

        $response = $this->post('/login', [
            'email' => $modifiedEmail,
            'password' => 'password',
        ]);

        $this->assertGuest();
    }
    public function test_redirect_to_facebook_gets_rendered()
    {
        $response = $this->get('/auth/facebook/');
        $response->assertRedirect();
    }
    public function test_facebook_callback_for_existing_user_works()
    {
        $user = User::create([
            'email' => 'user@example.com',
            'password' => Hash::make('password'),  
        ]);

        Socialite::shouldReceive('driver->stateless->user')->andReturn((object)[
            'email' => 'user@example.com',
        ]);

        $response = $this->get('/auth/facebook/callback');
        $response->assertRedirect('/dashboard');
    }

    public function test_handle_facebook_callback_new_user()
    {
        Socialite::shouldReceive('driver->stateless->user')->andReturn((object)[
            'email' => 'newuser@example.com',
        ]);

        $response = $this->get('/auth/facebook/callback');
        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
    }

    public function test_redirect_to_gmail_gets_rendered()
    {
        $response = $this->get('/auth/google/');
        $response->assertRedirect();
    }
    public function test_gmail_callback_for_existing_user_works()
    {
        $user = User::create([
            'email' => 'user@example.com',
            'password' => Hash::make('password'),  
        ]);

        Socialite::shouldReceive('driver->stateless->user')->andReturn((object)[
            'email' => 'user@example.com',
        ]);

        $response = $this->get('/auth/google/callback');
        $response->assertRedirect('/dashboard');
    }

    public function test_handle_gmail_callback_new_user()
    {
        Socialite::shouldReceive('driver->stateless->user')->andReturn((object)[
            'email' => 'newuser@example.com',
        ]);

        $response = $this->get('/auth/google/callback');
        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
    }

    public function test_redirect_to_apple_gets_rendered()
    {
        $response = $this->get('/auth/apple/');
        $response->assertRedirect();
    }
    public function test_apple_callback_for_existing_user_works()
    {
        $user = User::create([
            'email' => 'user@example.com',
            'password' => Hash::make('password'),  
        ]);

        Socialite::shouldReceive('driver->stateless->user')->andReturn((object)[
            'email' => 'user@example.com',
        ]);

        $response = $this->get('/auth/apple/callback');
        $response->assertRedirect('/dashboard');
    }

    public function test_handle_apple_callback_new_user()
    {
        Socialite::shouldReceive('driver->stateless->user')->andReturn((object)[
            'email' => 'newuser@example.com',
        ]);

        $response = $this->get('/auth/apple/callback');
        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
    }
}
