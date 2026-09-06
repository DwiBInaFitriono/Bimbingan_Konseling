<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginGuru(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login_guru');
    }

    public function login(Request $request): RedirectResponse
    {
        $loginCredentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
            'role'     => ['required', 'in:guru_bk,siswa']
        ]);

        $authenticationAttemptData = [
            'email' => $loginCredentials['email'],
            'password' => $loginCredentials['password'],
            'role' => $loginCredentials['role']
        ];

        if (Auth::attempt($authenticationAttemptData, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->route('dashboard')->with('success', 'Login berhasil. Selamat bertugas!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validatedRegistrationData = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $registeredUser = User::create([
            'name'     => $validatedRegistrationData['name'],
            'email'    => $validatedRegistrationData['email'],
            'password' => Hash::make($validatedRegistrationData['password']),
            'role'     => 'guru_bk',
        ]);

        Auth::login($registeredUser);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Registrasi akun Guru BK berhasil. Selamat bertugas!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah keluar dari sistem.');
    }
}