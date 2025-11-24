<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kepegawaian extends Model
{
    use HasFactory;

    protected $table = 'kepegawaians';

    protected $fillable = [
        'sasaran_strategis',
        'indikator_kinerja',
        'target',
        'rumus',
        'input_1',
        'input_2',
        'realisasi',
        'capaian',
    ];

    protected $casts = [
        'target' => 'decimal:2',
        'realisasi' => 'decimal:2',
        'capaian' => 'decimal:2',
        'input_1' => 'integer',
        'input_2' => 'integer',
    ];
}