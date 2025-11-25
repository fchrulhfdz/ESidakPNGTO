<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Check if user has access to the current section
     */
    protected function checkAccess($requiredRole)
    {
        $user = auth()->user();
        
        if (!$user) {
            abort(403, 'Anda harus login untuk mengakses halaman ini.');
        }
        
        if ($user->role === 'super_admin') {
            return true; // Super admin can access everything
        }
        
        if ($user->role === $requiredRole) {
            return true;
        }
        
        abort(403, 'Akses ditolak. Role Anda: ' . $user->bagian_name . ', Diperlukan: ' . $this->getRoleName($requiredRole));
    }

    /**
     * Get human-readable role name
     */
    protected function getRoleName($role)
    {
        $roleNames = [
            'super_admin' => 'Super Admin',
            'perdata' => 'Perdata',
            'pidana' => 'Pidana',
            'tipikor' => 'Tipikor',
            'phi' => 'PHI',
            'hukum' => 'Hukum',
            'ptip' => 'PTIP',
            'umum_keuangan' => 'Umum & Keuangan',
            'kepegawaian' => 'Kepegawaian',
        ];

        return $roleNames[$role] ?? $role;
    }

    /**
     * Check if current user is super admin
     */
    protected function isSuperAdmin()
    {
        $user = auth()->user();
        return $user && $user->role === 'super_admin';
    }

    /**
     * Check if current user has specific role
     */
    protected function hasRole($role)
    {
        $user = auth()->user();
        return $user && $user->role === $role;
    }
}