<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PtipSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            [
                'sasaran_strategis' => '2 Meningkatnya Tingkat Keyakinan dan Kepercayaan Publik',
                'indikator_kinerja' => '2.1 Indeks kepuasan pengguna layanan pengadilan berdasarkan standar layanan yang ditetapkan',
                'target' => '85.0',
                'rumus' => '(Jumlah skor capaian Sistem Manajemen Anti Penyuapan / Jumlah skor maksimal) x 100%',
                'input_1' => null,
                'input_2' => null,
                'realisasi' => null,
                'capaian' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'sasaran_strategis' => '3 Terwujudnya Manajemen Peradilan yang Transparan dan Profesional',
                'indikator_kinerja' => '3.2 Nilai Indikator Kinerja Pelaksanaan Anggaran (IKPA) Satuan Kerja Pengadilan',
                'target' => '85.0',
                'rumus' => '(Jumlah skor capaian Sistem Manajemen Anti Penyuapan / Jumlah skor maksimal) x 100%',
                'input_1' => null,
                'input_2' => null,
                'realisasi' => null,
                'capaian' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'sasaran_strategis' => '3 Terwujudnya Manajemen Peradilan yang Transparan dan Profesional',
                'indikator_kinerja' => '3.3 Nilai kinerja perencanaan',
                'target' => '85.0',
                'rumus' => '(Jumlah skor capaian Sistem Manajemen Anti Penyuapan / Jumlah skor maksimal) x 100%',
                'input_1' => null,
                'input_2' => null,
                'realisasi' => null,
                'capaian' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($data as &$item) {
            $item['created_at'] = $now;
            $item['updated_at'] = null;
        }

        DB::table('ptips')->insert($data);
    }
}
