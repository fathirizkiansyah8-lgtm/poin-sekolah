<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class SingleActiveSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_session_is_rejected_when_another_device_becomes_active(): void
    {
        $user = User::factory()->create([
            'role' => 'teacher',
            'status' => 'active',
            'password' => 'correct-password',
        ]);

        $this->post(route('login.process'), [
            'email' => $user->email,
            'password' => 'correct-password',
        ])->assertRedirect(route('dashboard'));

        $originalSessionToken = $user->fresh()->active_session_token;

        $this->assertNotNull($originalSessionToken);

        $user->forceFill(['active_session_token' => 'another-device-session'])->save();
        Auth::forgetGuards();

        $this->get(route('dashboard'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_logout_clears_the_active_session_for_the_user(): void
    {
        $user = User::factory()->create([
            'role' => 'teacher',
            'status' => 'active',
            'password' => 'correct-password',
        ]);

        $this->post(route('login.process'), [
            'email' => $user->email,
            'password' => 'correct-password',
        ])->assertRedirect(route('dashboard'));

        $this->get(route('dashboard'))->assertOk();

        $this->post(route('logout'))->assertRedirect(route('login'));

        $this->assertNull($user->fresh()->active_session_token);
    }
}
