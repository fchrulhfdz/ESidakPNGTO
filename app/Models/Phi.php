<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phi extends Model
{
    use HasFactory;

    protected $table = 'phis';

    protected $fillable = [
        'sasaran_strategis',
        'indikator_kinerja',
        'target',
        'rumus',
        'label_input_1',
        'label_input_2', 
        'input_1',
        'input_2',
        'realisasi',
        'capaian',
        'bulan',
        'tahun',
    ];

    protected $casts = [
        'target' => 'decimal:2',
        'realisasi' => 'decimal:2',
        'capaian' => 'decimal:2',
        'bulan' => 'integer',
        'tahun' => 'integer',
        'input_1' => 'integer',
        'input_2' => 'integer',
    ];

    // Tambahkan mutator untuk handle nilai decimal
    public function setTargetAttribute($value)
    {
        $this->attributes['target'] = $this->cleanDecimalValue($value);
    }

    public function setRealisasiAttribute($value)
    {
        $this->attributes['realisasi'] = $this->cleanDecimalValue($value);
    }

    public function setCapaianAttribute($value)
    {
        $this->attributes['capaian'] = $this->cleanDecimalValue($value);
    }

    public function setInput1Attribute($value)
    {
        $this->attributes['input_1'] = $value ? (int) $value : 0;
    }

    public function setInput2Attribute($value)
    {
        $this->attributes['input_2'] = $value ? (int) $value : 0;
    }

    public function setBulanAttribute($value)
    {
        $this->attributes['bulan'] = $value ? (int) $value : null;
    }

    public function setTahunAttribute($value)
    {
        $this->attributes['tahun'] = $value ? (int) $value : null;
    }

    // Mutator untuk label input dengan nilai default untuk PHI
    public function setLabelInput1Attribute($value)
    {
        $this->attributes['label_input_1'] = $value ?: 'Jumlah Perkara PHI Diselesaikan';
    }

    public function setLabelInput2Attribute($value)
    {
        $this->attributes['label_input_2'] = $value ?: 'Jumlah Perkara PHI Tepat Waktu';
    }

    // Accessor untuk label input (jika kosong, berikan nilai default untuk PHI)
    public function getLabelInput1Attribute($value)
    {
        return $value ?: 'Jumlah Perkara PHI Diselesaikan';
    }

    public function getLabelInput2Attribute($value)
    {
        return $value ?: 'Jumlah Perkara PHI Tepat Waktu';
    }

    private function cleanDecimalValue($value)
    {
        if (is_null($value) || $value === '') {
            return 0;
        }
        
        // Handle string dengan koma
        if (is_string($value)) {
            $value = str_replace(',', '.', $value);
        }
        
        return (float) $value;
    }

    // Scope untuk filter bulan dan tahun
    public function scopeFilterByPeriod($query, $bulan, $tahun)
    {
        return $query->where('bulan', $bulan)->where('tahun', $tahun);
    }

    // Scope untuk filter berdasarkan jenis perkara (jika diperlukan)
    public function scopePhi($query)
    {
        return $query->where('jenis', 'phi');
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

    // Method untuk mendapatkan label input yang aman (tidak null) untuk PHI
    public function getSafeLabelInput1()
    {
        return $this->label_input_1 ?: 'Jumlah Perkara PHI Diselesaikan';
    }

    public function getSafeLabelInput2()
    {
        return $this->label_input_2 ?: 'Jumlah Perkara PHI Tepat Waktu';
    }

    // Method untuk menghitung realisasi dan capaian otomatis
    public function calculatePerformance()
    {
        if ($this->input_1 && $this->input_2) {
            // Hitung realisasi: (input_2 / input_1) * 100
            if ($this->input_1 > 0) {
                $this->realisasi = ($this->input_2 / $this->input_1) * 100;
            } else {
                $this->realisasi = 0;
            }

            // Hitung capaian: (realisasi / target) * 100
            if ($this->target > 0) {
                $this->capaian = ($this->realisasi / $this->target) * 100;
            } else {
                $this->capaian = 0;
            }
        }
    }

    // Event boot untuk auto-calculate sebelum save
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->calculatePerformance();
        });
    }

    // Method untuk mendapatkan data berdasarkan periode
    public static function getByPeriod($bulan, $tahun)
    {
        return static::where('bulan', $bulan)
                    ->where('tahun', $tahun)
                    ->get();
    }

    // Method untuk mendapatkan total realisasi dan capaian
    public static function getTotalPerformance($bulan = null, $tahun = null)
    {
        $query = static::query();
        
        if ($bulan && $tahun) {
            $query->where('bulan', $bulan)->where('tahun', $tahun);
        }

        $data = $query->get();

        $totalRealisasi = $data->sum('realisasi');
        $totalCapaian = $data->sum('capaian');
        $count = $data->count();

        return [
            'total_realisasi' => $count > 0 ? $totalRealisasi / $count : 0,
            'total_capaian' => $count > 0 ? $totalCapaian / $count : 0,
            'count' => $count
        ];
    }

    // Method untuk mendapatkan statistik bulanan
    public static function getMonthlyStats($tahun)
    {
        return static::where('tahun', $tahun)
                    ->selectRaw('bulan, AVG(realisasi) as avg_realisasi, AVG(capaian) as avg_capaian')
                    ->groupBy('bulan')
                    ->orderBy('bulan')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->bulan => [
                            'avg_realisasi' => floatval($item->avg_realisasi),
                            'avg_capaian' => floatval($item->avg_capaian)
                        ]];
                    });
    }

    // Method untuk memeriksa duplikasi sasaran strategis pada periode yang sama
    public static function isDuplicate($sasaranStrategis, $bulan, $tahun, $excludeId = null)
    {
        $query = static::where('sasaran_strategis', $sasaranStrategis)
                      ->where('bulan', $bulan)
                      ->where('tahun', $tahun);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}