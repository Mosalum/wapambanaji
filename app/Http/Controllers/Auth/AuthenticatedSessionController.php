<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    public function create(): Response { return Inertia::render('Auth/Login'); }

    public function store(LoginRequest $request): RedirectResponse
    {
        $key = 'login:'.$request->ip();
        abort_if(RateLimiter::tooManyAttempts($key, 5), 429, 'Too many login attempts.');
        $login = preg_replace('/\s+/', '', $request->validated('login'));
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        if (! Auth::attempt([$field => $login, 'password' => $request->validated('password')], $request->boolean('remember'))) {
            RateLimiter::hit($key, 60);
            return back()->withErrors(['login' => 'Invalid credentials.']);
        }
        RateLimiter::clear($key);
        $request->session()->regenerate();
        $request->user()->update(['last_login_at' => now()]);
        return redirect()->route('dashboard');
    }
}
