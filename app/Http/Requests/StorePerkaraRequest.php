<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePerkaraRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // Rules dasar
        $rules = [
            'jenis' => 'required|string|in:perdata,pidana,tipikor,phi,hukum',
        ];

        // Jika ada input sasaran_strategis (form tambah data super admin)
        if ($this->has('sasaran_strategis') && !$this->has('input_1')) {
            $rules['sasaran_strategis'] = 'required|string|max:255';
            $rules['indikator_kinerja'] = 'required|string|max:255';
            $rules['target'] = 'required|numeric|min:0|max:100';
            $rules['rumus'] = 'required|string|max:500';
        }

        // Jika ada input_1 dan input_2 (form input data perkara)
        if ($this->has('input_1') && $this->has('input_2')) {
            $rules['input_1'] = 'required|integer|min:0';
            $rules['input_2'] = 'required|integer|min:0';
            
            // Untuk admin biasa, sasaran strategis diambil dari hidden field
            if ($this->has('sasaran_hidden')) {
                $rules['sasaran_hidden'] = 'required|string';
                $rules['indikator_hidden'] = 'required|string';
                $rules['target_hidden'] = 'required|numeric';
                $rules['rumus_hidden'] = 'required|string';
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'input_1.required' => 'Jumlah perkara diselesaikan wajib diisi',
            'input_2.required' => 'Jumlah perkara tepat waktu wajib diisi',
            'jenis.in' => 'Jenis perkara tidak valid',
            'sasaran_hidden.required' => 'Pilih sasaran strategis terlebih dahulu',
        ];
    }
}