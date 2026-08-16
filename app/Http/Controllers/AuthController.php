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
            return Auth::user()->isSiswa()
                ? redirect()->route('counseling.siswa')
                : redirect()->route('dashboard');
        }

        return view('auth.login_guru');
    }

    public function showLoginSiswa(): View|RedirectResponse
    {
        if (Auth::check()) {
            return Auth::user()->isSiswa()
                ? redirect()->route('counseling.siswa')
                : redirect()->route('dashboard');
        }

        return view('auth.login_siswa');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
            'role'     => ['required', 'in:guru_bk,siswa']
        ]);

        // Melakukan validasi Auth lengkap dengan role-nya agar siswa tak bisa login di form guru
        $attemptData = [
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'role' => $credentials['role']
        ];

        if (Auth::attempt($attemptData, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->isSiswa()) {
                return redirect()->route('counseling.siswa')->with('success', 'Selamat datang di Layanan Konseling Siswa.');
            }

            return redirect()->route('dashboard')->with('success', 'Login berhasil. Selamat bertugas!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return Auth::user()->isSiswa()
                ? redirect()->route('counseling.siswa')
                : redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        // Registrasi publik khusus untuk Guru BK
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'guru_bk',
        ]);

        Auth::login($user);
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