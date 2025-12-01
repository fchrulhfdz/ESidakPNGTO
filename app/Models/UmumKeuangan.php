<?php
// app/Models/UmumKeuangan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmumKeuangan extends Model
{
    use HasFactory;

    protected $table = 'umum_keuangans';

    protected $fillable = [
        'sasaran_strategis',
        'indikator_kinerja',
        'target',
        'label_input_1',
        'input_1',
        'bulan',
        'tahun',
    ];

    protected $casts = [
        'target' => 'decimal:2',
        'bulan' => 'integer',
        'tahun' => 'integer',
        'input_1' => 'integer',
    ];

    // Relasi ke lampiran
    public function lampirans()
    {
        return $this->hasMany(UmumKeuanganLampiran::class);
    }

    // Scope untuk filter periode
    public function scopeFilterByPeriod($query, $bulan = null, $tahun = null)
    {
        if ($bulan) {
            $query->where('bulan', $bulan);
        }
        if ($tahun) {
            $query->where('tahun', $tahun);
        }
        return $query;
    }

    // Accessor untuk nama bulan
    public function getNamaBulanAttribute()
    {
        $bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        
        return $bulan[$this->bulan] ?? 'Tidak diketahui';
    }

    // Accessor untuk cek apakah sudah ada input
    public function getHasInputAttribute()
    {
        return !is_null($this->input_1) && $this->input_1 > 0;
    }

    // Scope untuk data yang belum diisi
    public function scopeBelumDiisi($query)
    {
        return $query->whereNull('input_1')->orWhere('input_1', 0);
    }

    // Scope untuk data yang sudah diisi
    public function scopeSudahDiisi($query)
    {
        return $query->whereNotNull('input_1')->where('input_1', '>', 0);
    }

    // Method untuk mendapatkan data berdasarkan sasaran strategis
    public static function getBySasaranStrategis($sasaranStrategis, $bulan = null, $tahun = null)
    {
        $query = self::where('sasaran_strategis', $sasaranStrategis);
        
        if ($bulan) {
            $query->where('bulan', $bulan);
        }
        
        if ($tahun) {
            $query->where('tahun', $tahun);
        }
        
        return $query->get();
    }
}