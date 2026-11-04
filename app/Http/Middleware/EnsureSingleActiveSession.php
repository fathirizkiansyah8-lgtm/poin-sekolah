<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class EnsureSingleActiveSession
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        $sessionToken = $request->session()->get('active_login_token');

        if ($user->active_session_token === null && $sessionToken === null) {
            $sessionToken = Str::random(64);
            $request->session()->put('active_login_token', $sessionToken);
            $user->forceFill(['active_session_token' => $sessionToken])->save();
        } elseif (! $sessionToken || ! $user->active_session_token || ! hash_equals($user->active_session_token, $sessionToken)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Akun ini sedang digunakan di browser atau perangkat lain. Silakan login kembali.',
            ]);
        }

        return $next($request);
    }
}
