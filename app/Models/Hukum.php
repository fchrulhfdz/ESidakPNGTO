<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hukum extends Model
{
    use HasFactory;

    protected $table = 'hukums';

    protected $fillable = [
        'sasaran_strategis',
        'indikator_kinerja',
        'target',
        'rumus',
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
}