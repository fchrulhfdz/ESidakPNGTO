<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth()->user();
        
        // Super admin memiliki akses penuh
        if ($user->role === 'super_admin') {
            return $next($request);
        }
        
        // Cek apakah user memiliki salah satu role yang diizinkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }
        
        // Cek untuk read_only role - hanya boleh akses GET method
        if ($user->role === 'read_only') {
            if ($request->isMethod('GET')) {
                return $next($request);
            }
            abort(403, 'Anda hanya memiliki akses read-only.');
        }
        
        abort(403, 'Unauthorized access.');
    }
}