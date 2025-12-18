<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PidanaLampiran extends Model
{
    use HasFactory;

    protected $table = 'pidana_lampirans';

    protected $fillable = [
        'pidana_id',
        'user_id',
        'nama_file',
        'path',
        'original_name',
        'file_size',
        'mime_type',
        'keterangan',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

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

    // Accessor untuk icon berdasarkan tipe file
    public function getFileIconAttribute()
    {
        $mime = $this->mime_type;
        
        if (str_contains($mime, 'image')) {
            return 'fa-file-image';
        } elseif (str_contains($mime, 'pdf')) {
            return 'fa-file-pdf';
        } elseif (str_contains($mime, 'word') || str_contains($mime, 'document')) {
            return 'fa-file-word';
        } elseif (str_contains($mime, 'excel') || str_contains($mime, 'spreadsheet')) {
            return 'fa-file-excel';
        } elseif (str_contains($mime, 'powerpoint') || str_contains($mime, 'presentation')) {
            return 'fa-file-powerpoint';
        } else {
            return 'fa-file';
        }
    }

    // Accessor untuk warna icon
    public function getFileIconColorAttribute()
    {
        $mime = $this->mime_type;
        
        if (str_contains($mime, 'pdf')) {
            return 'text-danger';
        } elseif (str_contains($mime, 'word') || str_contains($mime, 'document')) {
            return 'text-primary';
        } elseif (str_contains($mime, 'excel') || str_contains($mime, 'spreadsheet')) {
            return 'text-success';
        } elseif (str_contains($mime, 'powerpoint') || str_contains($mime, 'presentation')) {
            return 'text-warning';
        } else {
            return 'text-secondary';
        }
    }

    // Accessor untuk ekstensi file
    public function getFileExtensionAttribute()
    {
        return pathinfo($this->original_name, PATHINFO_EXTENSION);
    }

    // Relasi ke model Pidana
    public function pidana()
    {
        return $this->belongsTo(Pidana::class);
    }

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk filter berdasarkan pidana_id
    public function scopeByPidana($query, $pidanaId)
    {
        return $query->where('pidana_id', $pidanaId);
    }

    // Scope untuk filter berdasarkan user_id
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Method untuk mendapatkan URL lengkap file
    public function getFullUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }

    // Method untuk mengecek apakah file adalah gambar
    public function isImage()
    {
        return str_contains($this->mime_type, 'image');
    }

    // Method untuk mengecek apakah file adalah PDF
    public function isPdf()
    {
        return str_contains($this->mime_type, 'pdf');
    }

    // Accessor untuk keterangan yang aman
    public function getSafeKeteranganAttribute()
    {
        return $this->keterangan ?: 'Tidak ada keterangan';
    }
}