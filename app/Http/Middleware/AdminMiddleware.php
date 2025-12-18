<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            \Log::info('AdminMiddleware: User not authenticated');
            return redirect()->route('login');
        }

        $user = auth()->user();
        $userRole = strtolower(trim($user->role));
        
        // LOG DETAIL
        \Log::info('=== ADMIN MIDDLEWARE START ===', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role' => $userRole,
            'user_role_raw' => $user->role,
            'path' => $request->path(),
            'method' => $request->method(),
            'url' => $request->url(),
            'full_url' => $request->fullUrl(),
            'route_name' => $request->route() ? $request->route()->getName() : null
        ]);

        // Daftar SEMUA role yang diizinkan
        $allowedRoles = [
            'admin',
            'super_admin',
            'perdata',
            'pidana', 
            'phi',
            'tipikor',
            'hukum',
            'ptip',
            'kepegawaian',
            'umum_keuangan',
            'read_only',
        ];

        // Convert semua ke lowercase untuk comparison
        $allowedRolesLower = array_map('strtolower', $allowedRoles);
        $userRoleLower = strtolower($userRole);

        // Cek apakah role ada di array
        $isAllowed = in_array($userRoleLower, $allowedRolesLower);
        
        \Log::info('Role check result:', [
            'user_role_lower' => $userRoleLower,
            'allowed_roles_lower' => $allowedRolesLower,
            'is_allowed' => $isAllowed ? 'YES' : 'NO',
            'match_found' => $isAllowed ? 'Role matched' : 'Role NOT in allowed list'
        ]);

        if (!$isAllowed) {
            \Log::warning("ACCESS DENIED - User ID: {$user->id}, Role: '{$userRole}'");
            \Log::warning("User trying to access: " . $request->fullUrl());
            abort(403, "Akses ditolak. Role '{$userRole}' tidak diizinkan untuk mengakses halaman ini.");
        }

        \Log::info('ACCESS GRANTED - Continuing to next middleware/controller');
        \Log::info('=== ADMIN MIDDLEWARE END ===');
        
        return $next($request);
    }
}