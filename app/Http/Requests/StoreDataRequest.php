<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDataRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'jenis' => 'required|string',
            'sasaran_strategis' => 'required|string|max:255',
            'indikator_kinerja' => 'sometimes|required|string|max:255',
            'target' => 'required|numeric|min:0|max:100',
            'rumus' => 'required|string|max:500',
            'input_1' => 'required|integer|min:0',
            'input_2' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'input_1.required' => 'Jumlah perkara diselesaikan wajib diisi',
            'input_2.required' => 'Jumlah perkara tepat waktu wajib diisi',
        ];
    }
}