<?php

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
        'capaian',
        'status_capaian',
        'hambatan',
        'rekomendasi',
        'tindak_lanjut',
        'keberhasilan',
        'bulan',
        'tahun',
    ];

    protected $casts = [
        'target' => 'decimal:2',
        'capaian' => 'decimal:2',
        'bulan' => 'integer',
        'tahun' => 'integer',
        'input_1' => 'integer',
    ];

    public function lampirans()
    {
        return $this->hasMany(UmumKeuanganLampiran::class);
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

    public function getHasInputAttribute()
    {
        return !is_null($this->input_1) && $this->input_1 > 0;
    }

    public function scopeUniqueSasaranStrategis($query)
    {
        return $query->select('sasaran_strategis', 'id', 'indikator_kinerja', 'target', 'label_input_1')
                    ->whereNull('input_1')
                    ->groupBy('sasaran_strategis')
                    ->orderBy('sasaran_strategis');
    }

    public static function getUniqueSasaranStrategis()
    {
        return self::select('sasaran_strategis', 'id', 'indikator_kinerja', 'target', 'label_input_1')
                  ->whereNull('input_1')
                  ->distinct('sasaran_strategis')
                  ->orderBy('sasaran_strategis')
                  ->get();
    }

    public function isTemplate()
    {
        return is_null($this->input_1);
    }

    public static function getUniqueByIndikator()
    {
        return self::select('indikator_kinerja', 'id', 'sasaran_strategis', 'bulan', 'tahun')
                  ->whereNotNull('input_1')
                  ->distinct('indikator_kinerja')
                  ->orderBy('indikator_kinerja')
                  ->get();
    }

    public function scopeByIndikator($query, $indikator)
    {
        return $query->where('indikator_kinerja', 'like', '%' . $indikator . '%');
    }

    public function hitungCapaian()
    {
        if ($this->target > 0 && $this->input_1 !== null) {
            $this->capaian = $this->input_1 / $this->target;
            
            if ($this->capaian >= 1) {
                $this->status_capaian = 'Tercapai';
            } elseif ($this->capaian >= 0.8) {
                $this->status_capaian = 'Hampir Tercapai';
            } else {
                $this->status_capaian = 'Belum Tercapai';
            }
            
            return $this->capaian;
        }
        
        return null;
    }

    public function getCapaianFormattedAttribute()
    {
        if ($this->capaian !== null) {
            return number_format($this->capaian, 2);
        }
        return '-';
    }

    public function getCapaianPersenAttribute()
    {
        if ($this->capaian !== null) {
            return number_format($this->capaian * 100, 2) . '%';
        }
        return '-';
    }

    // Accessor untuk kolom analisis
    public function getHambatanAttribute($value)
    {
        return $value ?: '-';
    }

    public function getRekomendasiAttribute($value)
    {
        return $value ?: '-';
    }

    public function getTindakLanjutAttribute($value)
    {
        return $value ?: '-';
    }

    public function getKeberhasilanAttribute($value)
    {
        return $value ?: '-';
    }

    // Cek apakah data memiliki analisis
    public function getHasAnalisisAttribute()
    {
        return !empty($this->hambatan) || !empty($this->rekomendasi) || 
               !empty($this->tindak_lanjut) || !empty($this->keberhasilan);
    }
}