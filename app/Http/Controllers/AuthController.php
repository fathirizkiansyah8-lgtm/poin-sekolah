<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private const MAX_LOGIN_ATTEMPTS = 3;

    private const LOGIN_ATTEMPT_DECAY_SECONDS = 60;

    private const LOGIN_LOCKOUT_SECONDS = 60;

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');

    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $emailKey = Str::lower($credentials['email']);
        $throttleKey = 'login:'.hash('sha256', $emailKey.'|'.$request->ip());
        $lockoutKey = $throttleKey.':lockout';

        if (RateLimiter::tooManyAttempts($lockoutKey, 1)) {
            return $this->loginLockedResponse($lockoutKey);
        }

        if (Auth::attempt($credentials)) {
            RateLimiter::clear($throttleKey);
            RateLimiter::clear($lockoutKey);
            $request->session()->regenerate();
            $sessionToken = Str::random(64);
            $request->session()->put('active_login_token', $sessionToken);
            Auth::user()->forceFill([
                'active_session_token' => $sessionToken,
            ])->save();

            if (auth()->user()->role === 'student') {
                return redirect()->route('student.points');
            }

            return redirect()->intended(route('dashboard'));
        }

        RateLimiter::hit($throttleKey, self::LOGIN_ATTEMPT_DECAY_SECONDS);

        if (RateLimiter::attempts($throttleKey) >= self::MAX_LOGIN_ATTEMPTS) {
            RateLimiter::clear($throttleKey);
            RateLimiter::hit($lockoutKey, self::LOGIN_LOCKOUT_SECONDS);

            return $this->loginLockedResponse($lockoutKey);
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->onlyInput('email');
    }

    private function loginLockedResponse(string $lockoutKey)
    {
        $seconds = RateLimiter::availableIn($lockoutKey);

        return back()
            ->withErrors([
                'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
            ])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user && $user->active_session_token === $request->session()->get('active_login_token')) {
            $user->forceFill(['active_session_token' => null])->save();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
