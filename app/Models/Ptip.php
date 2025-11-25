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

    // Mutator untuk label input (jika diperlukan)
    public function setLabelInput1Attribute($value)
    {
        $this->attributes['label_input_1'] = $value ?: 'Jumlah Kegiatan PTIP';
    }

    public function setLabelInput2Attribute($value)
    {
        $this->attributes['label_input_2'] = $value ?: 'Jumlah Kegiatan Tepat Waktu';
    }

    // Accessor untuk label input (jika kosong, berikan nilai default)
    public function getLabelInput1Attribute($value)
    {
        return $value ?: 'Jumlah Kegiatan PTIP';
    }

    public function getLabelInput2Attribute($value)
    {
        return $value ?: 'Jumlah Kegiatan Tepat Waktu';
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

    // Method untuk mendapatkan label input yang aman (tidak null)
    public function getSafeLabelInput1()
    {
        return $this->label_input_1 ?: 'Jumlah Kegiatan PTIP';
    }

    public function getSafeLabelInput2()
    {
        return $this->label_input_2 ?: 'Jumlah Kegiatan Tepat Waktu';
    }
}