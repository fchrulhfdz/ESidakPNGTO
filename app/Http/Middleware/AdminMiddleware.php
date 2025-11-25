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
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $allowedRoles = [
            'perdata',
            'pidana', 
            'phi',
            'tipikor',
            'hukum',
            'ptip',
            'kepegawaian',
            'umum_keuangan', // PERBAIKAN: gunakan underscore untuk database
            'super_admin',
        ];

        $userRole = strtolower(trim(auth()->user()->role));

        if (!in_array($userRole, $allowedRoles)) {
            \Log::warning("Unauthorized access attempt by user ID: " . auth()->user()->id . " with role: " . auth()->user()->role);
            abort(403, 'Akses ditolak. Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }
}