<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Services\BannedWordChecker;
use App\Services\SettingsService;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Неверные учетные данные.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect('/');
    }

    public function showRegister(SettingsService $settings): View|RedirectResponse
    {
        if (!$settings->isRegistrationEnabled()) {
            return redirect()->route('home')->withErrors(['registration' => 'Регистрация временно закрыта администратором.']);
        }
        return view('auth.register');
    }

    public function register(Request $request, SettingsService $settings): RedirectResponse
    {
        if (!$settings->isRegistrationEnabled()) {
            return redirect()->route('home')->withErrors(['registration' => 'Регистрация временно закрыта.']);
        }
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if ($bannedWord = BannedWordChecker::getBannedWordInText($validated['name'])) {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['name' => "Имя содержит запрещённое слово: {$bannedWord}"]);
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        return redirect('/');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/auth');
    }
}
