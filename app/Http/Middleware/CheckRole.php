<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Jika siswa mencoba akses area guru bk, arahkan ke dashboard siswa
        if ($user->isSiswa()) {
            return redirect()->route('counseling.siswa')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
    }
}
