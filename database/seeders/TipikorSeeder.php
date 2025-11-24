<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TipikorSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $data = [

            // ===========================
            // SASARAN STRATEGIS 2
            // ===========================
            [
                'sasaran_strategis' => 'Meningkatnya Tingkat Keyakinan dan Kepercayaan Publik',
                'indikator_kinerja' => 'Indeks kepuasan pengguna layanan pengadilan berdasarkan standar layanan yang ditetapkan',
                'target' => '85.0',
                'rumus' => '(Jumlah skor capaian Sistem Manajemen Anti Penyuapan / Jumlah skor maksimal) x 100%',
                'input_1' => null,
                'input_2' => null,
                'realisasi' => null,
                'capaian' => null,
                'bulan' => null,
                'tahun' => 2025,
                'created_at' => $now,
                'updated_at' => null,
            ],

            // ===========================
            // SASARAN STRATEGIS 3
            // ===========================
            [
                'sasaran_strategis' => 'Terwujudnya Manajemen Peradilan yang Transparan dan Profesional',
                'indikator_kinerja' => 'Nilai Indikator Kinerja Pelaksanaan Anggaran (IKPA) Satuan Kerja Pengadilan',
                'target' => '85.0',
                'rumus' => '(Jumlah skor capaian Sistem Manajemen Anti Penyuapan / Jumlah skor maksimal) x 100%',
                'input_1' => null,
                'input_2' => null,
                'realisasi' => null,
                'capaian' => null,
                'bulan' => null,
                'tahun' => 2025,
                'created_at' => $now,
                'updated_at' => null,
            ],

            [
                'sasaran_strategis' => 'Terwujudnya Manajemen Peradilan yang Transparan dan Profesional',
                'indikator_kinerja' => 'Nilai kinerja perencanaan',
                'target' => '85.0',
                'rumus' => '(Jumlah skor capaian Sistem Manajemen Anti Penyuapan / Jumlah skor maksimal) x 100%',
                'input_1' => null,
                'input_2' => null,
                'realisasi' => null,
                'capaian' => null,
                'bulan' => null,
                'tahun' => 2025,
                'created_at' => $now,
                'updated_at' => null,
            ],
        ];

        // Pastikan nama tabel sesuai! Biasanya: tipikors
        DB::table('tipikor')->insert($data);
    }
}
