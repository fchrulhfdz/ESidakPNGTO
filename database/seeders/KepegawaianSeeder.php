<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KepegawaianSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            [
                'sasaran_strategis' => '3 Terwujudnya Manajemen Peradilan yang Transparan dan Profesional',
                'indikator_kinerja' => '3.1 Indeks Profesionalitas Aparatur Sipil Negara (IP ASN) Satuan Kerja Pengadilan',
                'target' => '85%',
                'rumus' => '(Jumlah skor capaian Sistem Manajemen Anti Penyuapan / Jumlah skor maksimal) x 100%',
                'input_1' => null,
                'input_2' => null,
                'realisasi' => null,
                'capaian' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('kepegawaian')->insert($data);
    }
}
