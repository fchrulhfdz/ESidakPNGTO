<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReadOnlyMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Hanya izinkan user dengan role 'read_only'
        $userRole = strtolower(trim(auth()->user()->role));
        
        if ($userRole !== 'read_only') {
            \Log::warning("Unauthorized read_only access attempt by user ID: " . auth()->user()->id . " with role: " . auth()->user()->role);
            abort(403, 'Akses ditolak. Hanya untuk role read_only.');
        }

        return $next($request);
    }
}