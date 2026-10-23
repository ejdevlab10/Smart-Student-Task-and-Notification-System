<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login = trim($credentials['login']);

        // Allow login using email or student ID.
        $field = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'student_id';

        $authCredentials = [
            $field => $login,
            'password' => $credentials['password'],
        ];

        // Prevent unapproved teachers from logging in.
        $user = \App\Models\User::where($field, $login)->first();

        if ($user && $user->role === 'teacher' && !$user->is_approved) {
            return back()
                ->withErrors([
                    'login' => 'Your teacher account is awaiting administrator approval.',
                ])
                ->onlyInput('login');
        }

        if (Auth::attempt($authCredentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('calendar.index'));
        }

        return back()
            ->withErrors([
                'login' => 'The provided credentials are incorrect.',
            ])
            ->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('status', 'You have been logged out successfully.');
    }
}