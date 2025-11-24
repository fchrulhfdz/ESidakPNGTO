<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PerdataSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            [
                'sasaran_strategis' => '1 Terwujudnya peradilan yang efektif, transparan, akuntabel dan modern.',
                'indikator_kinerja' => '1.1 Persentase penyelesaian perkara secara tepat waktu',
                'target' => 85.00,
                'rumus' => 'jumlah perkara yang telah diselesaikan tepat waktu dibagi jumlah perkara yang diselesaikan dikali 100%',
                'input_1' => 100,
                'input_2' => 80,
                'realisasi' => 80.00,
                'capaian' => 94.12,
                'bulan' => 1,
                'tahun' => 2025,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'sasaran_strategis' => '1 Terwujudnya peradilan yang efektif, transparan, akuntabel dan modern.',
                'indikator_kinerja' => '1.2 Persentase penyediaan/pengiriman salinan putusan tepat waktu oleh pengadilan tingkat pertama kepada para pihak',
                'target' => 85.00,
                'rumus' => 'jumlah perkara yang telah diselesaikan tepat waktu dibagi jumlah perkara yang diselesaikan dikali 100%',
                'input_1' => 150,
                'input_2' => 130,
                'realisasi' => 86.67,
                'capaian' => 101.96,
                'bulan' => 1,
                'tahun' => 2025,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // ... tambahkan data lainnya dengan bulan dan tahun yang sesuai
        ];

        DB::table('perdatas')->insert($data);
    }
}