<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtipLampiran extends Model
{
    use HasFactory;

    protected $table = 'ptip_lampirans';

    protected $fillable = [
        'ptip_id',
        'user_id',
        'nama_file',
        'path',
        'original_name',
        'file_size',
        'mime_type',
    ];

    // Relasi ke PTIP
    public function ptip()
    {
        return $this->belongsTo(Ptip::class);
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
}