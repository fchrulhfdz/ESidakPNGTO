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

    // Atribut tambahan untuk evaluasi kerja
    protected $appends = ['bagian_name'];

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
            'umum_keuangan' => 'Umum & Keuangan',
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
            'umum_keuangan' => 'umum-keuangan',
            'kepegawaian' => 'kepegawaian'
        ];

        return $routeMapping[$this->role] ?? 'dashboard';
    }

    /**
     * Relationship with EvaluasiKerja
     */
    public function evaluasiKerja()
    {
        return $this->hasMany(EvaluasiKerja::class, 'bagian', 'role');
    }

    /**
     * Check if user can upload evaluasi kerja
     * Sekarang semua user bisa upload
     */
    public function canUploadEvaluasiKerja()
    {
        return true; // Semua user yang login bisa upload
    }

    /**
     * Check if user can delete evaluasi kerja
     */
    public function canDeleteEvaluasiKerja()
    {
        return $this->isSuperAdmin();
    }

    /**
     * Get allowed bagian for evaluasi kerja
     */
    public function getAllowedBagianForEvaluasi()
    {
        if ($this->isSuperAdmin()) {
            return [
                'perdata' => 'Perdata',
                'pidana' => 'Pidana',
                'tipikor' => 'Tipikor',
                'phi' => 'PHI',
                'hukum' => 'Hukum',
                'ptip' => 'PTIP',
                'kepegawaian' => 'Kepegawaian',
                'umum_keuangan' => 'Umum & Keuangan',
            ];
        }
        
        return [$this->role => $this->bagian_name];
    }
}