<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'bagian'
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
        return $this->role === 'admin';
    }

    /**
     * Get the human-readable bagian name
     */
    public function getBagianNameAttribute()
    {
        $bagianNames = [
            'perdata' => 'Perdata',
            'pidana' => 'Pidana',
            'tipikor' => 'Tipikor',
            'phi' => 'PHI',
            'hukum' => 'Hukum',
            'ptip' => 'PTIP',
            'umum_keuangan' => 'Umum & Keuangan',
            'kepegawaian' => 'Kepegawaian',
        ];

        return $bagianNames[$this->bagian] ?? $this->bagian;
    }
}