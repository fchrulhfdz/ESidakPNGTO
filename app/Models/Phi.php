<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'status_capaian',
        'hambatan',
        'rekomendasi',
        'tindak_lanjut',
        'keberhasilan',
        'bulan',
        'tahun',
        'tipe_input',
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

    protected $appends = [
        'nama_bulan',
        'periode',
        'display_label_input_1',
        'display_label_input_2',
        'display_input_2',
        'is_dua_input',
        'is_satu_input',
        'formatted_target',
    ];

    // Mutator
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

    // Accessor untuk menentukan tipe input
    public function getIsDuaInputAttribute()
    {
        return $this->tipe_input === 'dua_input';
    }

    public function getIsSatuInputAttribute()
    {
        return $this->tipe_input === 'satu_input';
    }

    // Accessor untuk label input yang sesuai dengan tipe_input
    public function getDisplayLabelInput1Attribute()
    {
        if ($this->label_input_1) {
            return $this->label_input_1;
        }
        
        // Default label berdasarkan tipe_input
        if ($this->tipe_input === 'dua_input') {
            return 'Jumlah Perkara PHI Diselesaikan';
        } else {
            return 'Jumlah Perkara PHI';
        }
    }

    public function getDisplayLabelInput2Attribute()
    {
        if ($this->tipe_input === 'dua_input') {
            return $this->label_input_2 ?: 'Jumlah Perkara PHI Tepat Waktu';
        }
        
        return null; // Tidak ada label_input_2 untuk satu_input
    }

    // Accessor untuk menampilkan input_2 hanya jika dua_input
    public function getDisplayInput2Attribute()
    {
        if ($this->tipe_input === 'dua_input') {
            return $this->input_2;
        }
        
        return null; // Tidak ada input_2 untuk satu_input
    }

    // Accessor untuk menampilkan target dengan format yang sesuai
    public function getFormattedTargetAttribute()
    {
        if (!$this->target) {
            return '-';
        }
        
        $target = (float) $this->target;
        
        if ($this->tipe_input === 'dua_input') {
            return number_format($target, 2) . '%';
        } else {
            return number_format($target, 2);
        }
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

    private function cleanDecimalValue($value)
    {
        if (is_null($value) || $value === '') {
            return 0;
        }
        
        if (is_string($value)) {
            $value = str_replace(',', '.', $value);
        }
        
        return (float) $value;
    }

    // Scope untuk filter berdasarkan tipe_input
    public function scopeByTipeInput($query, $tipe_input)
    {
        return $query->where('tipe_input', $tipe_input);
    }

    // Scope untuk filter bulan dan tahun
    public function scopeFilterByPeriod($query, $bulan, $tahun)
    {
        return $query->where('bulan', $bulan)->where('tahun', $tahun);
    }

    // Scope untuk filter tahun saja
    public function scopeFilterByTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    // Scope untuk filter bulan saja
    public function scopeFilterByBulan($query, $bulan)
    {
        return $query->where('bulan', $bulan);
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $search)
    {
        return $query->where('sasaran_strategis', 'like', "%{$search}%")
                    ->orWhere('indikator_kinerja', 'like', "%{$search}%");
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

    // Accessor untuk periode
    public function getPeriodeAttribute()
    {
        if ($this->bulan && $this->tahun) {
            return $this->nama_bulan . ' ' . $this->tahun;
        }
        return null;
    }

    // Method untuk mendapatkan label input yang aman berdasarkan tipe_input
    public function getSafeLabelInput1()
    {
        if ($this->label_input_1) {
            return $this->label_input_1;
        }
        
        return $this->tipe_input === 'dua_input' ? 'Jumlah Perkara PHI Diselesaikan' : 'Jumlah Perkara PHI';
    }

    public function getSafeLabelInput2()
    {
        if ($this->tipe_input === 'dua_input') {
            return $this->label_input_2 ?: 'Jumlah Perkara PHI Tepat Waktu';
        }
        
        return null;
    }

    // Method untuk menghitung capaian otomatis berdasarkan tipe_input
    public function hitungCapaian()
    {
        if (!$this->target || $this->target == 0) {
            return 0;
        }
        
        if ($this->tipe_input === 'dua_input') {
            // Untuk dua_input: capaian = (realisasi / target) * 100
            if ($this->realisasi) {
                $capaian = ($this->realisasi / $this->target) * 100;
                return round($capaian, 2);
            }
        } else {
            // Untuk satu_input: capaian = (input_1 / target) * 100
            if ($this->input_1) {
                $capaian = ($this->input_1 / $this->target) * 100;
                return round($capaian, 2);
            }
        }
        
        return 0;
    }

    // Method untuk menentukan status capaian
    public function tentukanStatusCapaian()
    {
        $capaian = $this->capaian ?? $this->hitungCapaian();
        
        if ($capaian >= 100) {
            return 'Tercapai';
        } elseif ($capaian >= 80) {
            return 'Hampir Tercapai';
        } else {
            return 'Belum Tercapai';
        }
    }

    // Relasi dengan lampiran
    public function lampirans(): HasMany
    {
        return $this->hasMany(PhiLampiran::class);
    }

    // Relasi dengan user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Method untuk mengecek apakah memiliki lampiran
    public function hasLampiran(): bool
    {
        return $this->lampirans()->exists();
    }

    // Method untuk mendapatkan jumlah lampiran
    public function jumlahLampiran(): int
    {
        return $this->lampirans()->count();
    }

    // Method untuk mendapatkan badge class berdasarkan tipe_input
    public function getTipeInputBadgeClass()
    {
        return $this->tipe_input === 'dua_input' 
            ? 'bg-blue-100 text-blue-800' 
            : 'bg-green-100 text-green-800';
    }

    // Method untuk mendapatkan teks tipe_input
    public function getTipeInputText()
    {
        return $this->tipe_input === 'dua_input' 
            ? 'Dua Input' 
            : 'Satu Input';
    }
}