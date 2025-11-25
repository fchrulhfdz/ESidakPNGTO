<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin()
    {
        return $this->role !== 'super_admin';
    }

    /**
     * Get the human-readable bagian name from role
     */
    public function getBagianNameAttribute()
    {
        $bagianNames = [
            'super_admin' => 'Super Admin',
            'perdata' => 'Perdata',
            'pidana' => 'Pidana',
            'tipikor' => 'Tipikor',
            'phi' => 'PHI',
            'hukum' => 'Hukum',
            'ptip' => 'PTIP',
            'umum_keuangan' => 'Umum & Keuangan', // PERBAIKAN: underscore untuk database
            'kepegawaian' => 'Kepegawaian',
        ];

        return $bagianNames[$this->role] ?? $this->role;
    }

    /**
     * Get route name for user's bagian
     */
    public function getRouteForBagian()
    {
        $routeMapping = [
            'perdata' => 'perdata',
            'pidana' => 'pidana', 
            'tipikor' => 'tipikor',
            'phi' => 'phi',
            'hukum' => 'hukum',
            'ptip' => 'ptip',
            'umum_keuangan' => 'umum-keuangan', // PERBAIKAN: mapping ke route dengan dash
            'kepegawaian' => 'kepegawaian'
        ];

        return $routeMapping[$this->role] ?? 'dashboard';
    }
}