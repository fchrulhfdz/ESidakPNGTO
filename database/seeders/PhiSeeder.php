<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PhiSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            // ============================
            // SASARAN STRATEGIS 1
            // ============================
            [
                'sasaran_strategis' => '1 Terwujudnya peradilan yang efektif, transparan, akuntabel dan modern.',
                'indikator_kinerja' => '1.1 Persentase penyelesaian perkara secara tepat waktu',
                'target' => '85.0',
                'rumus' => '(Jumlah skor capaian Sistem Manajemen Anti Penyuapan / Jumlah skor maksimal) x 100%',
                'input_1' => null,
                'input_2' => null,
                'realisasi' => null,
                'capaian' => null,
                'bulan' => null,
                'tahun' => 2025,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'sasaran_strategis' => '1 Terwujudnya peradilan yang efektif, transparan, akuntabel dan modern.',
                'indikator_kinerja' => '1.2 Persentase penyedian/pengiriman salinan putusan tepat waktu oleh pengadilan tingkat pertama kepada para pihak',
                'target' => '85.0',
                'rumus' => '(Jumlah skor capaian Sistem Manajemen Anti Penyuapan / Jumlah skor maksimal) x 100%',
                'input_1' => null,
                'input_2' => null,
                'realisasi' => null,
                'capaian' => null,
                'bulan' => null,
                'tahun' => 2025,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // (lanjutan data lainnya tetap sama)

            [
                'sasaran_strategis' => '2 Meningkatnya Tingkat Keyakinan dan Kepercayaan Publik',
                'indikator_kinerja' => '2.1 Indeks kepuasan pengguna layanan pengadilan berdasarkan standar layanan yang ditetapkan',
                'target' => '85.0',
                'rumus' => '(Jumlah skor capaian Sistem Manajemen Anti Penyuapan / Jumlah skor maksimal) x 100%',
                'input_1' => null,
                'input_2' => null,
                'realisasi' => null,
                'capaian' => null,
                'bulan' => null,
                'tahun' => 2025,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('phis')->insert($data);
    }
}
