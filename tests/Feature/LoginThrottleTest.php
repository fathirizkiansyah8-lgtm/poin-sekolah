<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginThrottleTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_roles_are_locked_after_three_consecutive_failed_password_attempts(): void
    {
        foreach (['admin', 'teacher', 'student'] as $role) {
            $user = User::factory()->create([
                'email' => "{$role}@example.test",
                'role' => $role,
                'status' => 'active',
                'password' => 'correct-password',
            ]);

            foreach ([1, 2] as $attempt) {
                $this->post(route('login.process'), [
                    'email' => $user->email,
                    'password' => 'wrong-password',
                ])->assertSessionHasErrors('email');
            }

            $this->post(route('login.process'), [
                'email' => $user->email,
                'password' => 'wrong-password',
            ])
                ->assertSessionHasErrors('email')
                ->assertSessionHas('errors');

            $this->post(route('login.process'), [
                'email' => $user->email,
                'password' => 'correct-password',
            ])
                ->assertRedirect(route('login'));

            $this->assertGuest();
        }
    }

    public function test_failed_attempts_reset_after_successful_login_and_lockout_expires_after_one_minute(): void
    {
        $user = User::factory()->create([
            'email' => 'teacher@example.test',
            'role' => 'teacher',
            'status' => 'active',
            'password' => 'correct-password',
        ]);

        foreach ([1, 2] as $attempt) {
            $this->post(route('login.process'), [
                'email' => $user->email,
                'password' => 'wrong-password',
            ])->assertSessionHasErrors('email');
        }

        $this->post(route('login.process'), [
            'email' => $user->email,
            'password' => 'correct-password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->post(route('logout'));

        foreach ([1, 2, 3] as $attempt) {
            $response = $this->post(route('login.process'), [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);
        }

        $response->assertSessionHasErrors('email');
        $this->post(route('login.process'), [
            'email' => $user->email,
            'password' => 'correct-password',
        ])->assertRedirect(route('login'));

        $this->travel(61)->seconds();

        $this->post(route('login.process'), [
            'email' => $user->email,
            'password' => 'correct-password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }
}
