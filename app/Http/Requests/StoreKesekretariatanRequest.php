<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKesekretariatanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'jenis' => 'required|string|in:ptip,umum_keuangan,kepegawaian',
        ];

        if ($this->has('sasaran_strategis') && !$this->has('input_1')) {
            $rules['sasaran_strategis'] = 'required|string|max:255';
            $rules['indikator_kinerja'] = 'required|string|max:255';
            $rules['target'] = 'required|numeric|min:0|max:100';
            $rules['rumus'] = 'required|string|max:500';
        }

        if ($this->has('input_1') && $this->has('input_2')) {
            $rules['input_1'] = 'required|integer|min:0';
            $rules['input_2'] = 'required|integer|min:0';
            
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
            'sasaran_hidden.required' => 'Pilih sasaran strategis terlebih dahulu',
        ];
    }
}