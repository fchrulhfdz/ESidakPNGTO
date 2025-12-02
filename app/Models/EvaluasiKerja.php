<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EvaluasiKerja extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'evaluasi_kerja';

    protected $fillable = [
        'bagian',
        'nama_file',
        'path_file',
        'judul',
        'tahun',
        'bulan',
        'keterangan'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = ['bagian_name'];

    /**
     * Get bagian name
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
            'kepegawaian' => 'Kepegawaian',
            'umum_keuangan' => 'Umum & Keuangan',
            'super_admin' => 'Super Admin'
        ];

        return $bagianNames[$this->bagian] ?? $this->bagian;
    }

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'bagian', 'role');
    }

    /**
     * Scope untuk filter berdasarkan bagian
     */
    public function scopeByBagian($query, $bagian)
    {
        if ($bagian !== 'super_admin') {
            return $query->where('bagian', $bagian);
        }
        return $query;
    }

    /**
     * Get formatted created date
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->translatedFormat('d F Y H:i');
    }
}