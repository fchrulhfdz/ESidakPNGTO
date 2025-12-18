<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KepegawaianLampiran extends Model
{
    use HasFactory;

    protected $table = 'kepegawaian_lampirans';

    protected $fillable = [
        'kepegawaian_id',
        'user_id',
        'nama_file',
        'path',
        'original_name',
        'file_size',
        'mime_type',
    ];

    public function kepegawaian()
    {
        return $this->belongsTo(Kepegawaian::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function getFileSizeFormattedAttribute()
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
}