<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ptip extends Model
{
    use HasFactory;

    protected $table = 'ptips';

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

    public function lampirans()
    {
        return $this->hasMany(PtipLampiran::class);
    }

    public function scopeFilterByPeriod($query, $bulan, $tahun)
    {
        return $query->where('bulan', $bulan)->where('tahun', $tahun);
    }

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

    // Cek apakah data memiliki nilai input
    public function getHasInputAttribute()
    {
        return !is_null($this->input_1) && $this->input_1 > 0;
    }

    // Scope untuk mendapatkan sasaran strategis unik
    public function scopeUniqueSasaranStrategis($query)
    {
        return $query->select('sasaran_strategis', 'id', 'indikator_kinerja', 'target', 'label_input_1')
                    ->whereNull('input_1')
                    ->groupBy('sasaran_strategis')
                    ->orderBy('sasaran_strategis');
    }

    // Static method untuk mendapatkan semua sasaran strategis unik
    public static function getUniqueSasaranStrategis()
    {
        return self::select('sasaran_strategis', 'id', 'indikator_kinerja', 'target', 'label_input_1')
                  ->whereNull('input_1')
                  ->distinct('sasaran_strategis')
                  ->orderBy('sasaran_strategis')
                  ->get();
    }

    // Method untuk mendapatkan template sasaran strategis (input_1 null)
    public function isTemplate()
    {
        return is_null($this->input_1);
    }

    // Static method untuk mendapatkan data unik berdasarkan indikator kinerja
    public static function getUniqueByIndikator()
    {
        return self::select('indikator_kinerja', 'id', 'sasaran_strategis', 'bulan', 'tahun')
                  ->whereNotNull('input_1')
                  ->distinct('indikator_kinerja')
                  ->orderBy('indikator_kinerja')
                  ->get();
    }

    // Scope untuk mencari berdasarkan indikator kinerja
    public function scopeByIndikator($query, $indikator)
    {
        return $query->where('indikator_kinerja', 'like', '%' . $indikator . '%');
    }
}