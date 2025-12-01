<?php
// app/Models/UmumKeuanganLampiran.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmumKeuanganLampiran extends Model
{
    use HasFactory;

    protected $table = 'umum_keuangan_lampirans';

    protected $fillable = [
        'umum_keuangan_id',
        'user_id',
        'nama_file',
        'path',
        'original_name',
        'file_size',
        'mime_type',
    ];

    // Relasi ke UmumKeuangan
    public function umumKeuangan()
    {
        return $this->belongsTo(UmumKeuangan::class);
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor untuk format ukuran file
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    // Accessor untuk tanggal upload yang diformat
    public function getTanggalUploadAttribute()
    {
        return $this->created_at->translatedFormat('d F Y H:i');
    }

    // Scope untuk filter berdasarkan periode
    public function scopeFilterByPeriod($query, $bulan = null, $tahun = null)
    {
        return $query->whereHas('umumKeuangan', function($q) use ($bulan, $tahun) {
            if ($bulan) {
                $q->where('bulan', $bulan);
            }
            if ($tahun) {
                $q->where('tahun', $tahun);
            }
        });
    }

    // Scope untuk filter berdasarkan user
    public function scopeByUser($query, $userId = null)
    {
        if (!$userId && auth()->check()) {
            $userId = auth()->id();
        }
        return $query->where('user_id', $userId);
    }

    // Scope untuk filter berdasarkan umum_keuangan_id
    public function scopeByUmumKeuangan($query, $umumKeuanganId)
    {
        return $query->where('umum_keuangan_id', $umumKeuanganId);
    }

    // Scope untuk order by
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}