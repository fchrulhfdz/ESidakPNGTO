<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $bagian = null): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Super admin dapat mengakses semua
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        // Admin biasa - cek akses ke bagian
        if ($user->role === 'admin') {
            // Jika tidak ada parameter bagian, izinkan akses (untuk halaman umum seperti dashboard)
            if ($bagian === null) {
                return $next($request);
            }

            // Mapping URL ke nilai bagian di database
            $bagianMapping = [
                'perdata' => 'perdata',
                'pidana' => 'pidana',
                'tipikor' => 'tipikor',
                'phi' => 'phi',
                'hukum' => 'hukum',
                'ptip' => 'ptip',
                'umum-keuangan' => 'umum_keuangan',
                'kepegawaian' => 'kepegawaian'
            ];

            $dbBagian = $bagianMapping[$bagian] ?? $bagian;

            // Cek kecocokan bagian
            if ($user->bagian === $dbBagian) {
                return $next($request);
            }
        }

        abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
    }
}