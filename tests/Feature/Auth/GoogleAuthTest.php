<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirect_to_google(): void
    {
        $response = $this->get('/auth/google');
        $response->assertRedirectContains('accounts.google.com');
    }

    public function test_muci_user_is_logged_in_on_callback(): void
    {
        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->email = 'directora@muci.org';
        $socialiteUser->name = 'Directora Test';
        $socialiteUser->id = 'google-123';
        $socialiteUser->avatar = null;

        Socialite::shouldReceive('driver->user')->andReturn($socialiteUser);

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/tracker');
        $this->assertDatabaseHas('users', ['email' => 'directora@muci.org']);
        $this->assertAuthenticated();
    }

    public function test_non_muci_email_is_rejected(): void
    {
        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->email = 'hacker@gmail.com';
        $socialiteUser->name = 'Hacker';
        $socialiteUser->id = 'google-456';
        $socialiteUser->avatar = null;

        Socialite::shouldReceive('driver->user')->andReturn($socialiteUser);

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/tracker');
        $this->assertDatabaseMissing('users', ['email' => 'hacker@gmail.com']);
        $this->assertGuest();
    }

    public function test_logout_clears_session(): void
    {
        $user = User::factory()->create(['email' => 'dir@muci.org']);
        $this->actingAs($user);

        $response = $this->post('/auth/logout');

        $response->assertRedirect('/tracker');
        $this->assertGuest();
    }
}
