<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>LAPORAN E-SIDAK</title>
    <style>
        /* Reset dan dasar */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 1.5cm;
            color: #000000;
            background-color: #ffffff;
        }
        
        /* Header Laporan */
        .header-laporan {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px double #000;
            padding-bottom: 15px;
        }
        
        .header-laporan h1 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .header-laporan h2 {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .header-laporan h3 {
            font-size: 12pt;
            font-weight: normal;
            margin-bottom: 5px;
        }
        
        /* Informasi Periode */
        .info-periode {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
        }
        
        .info-periode p {
            margin: 3px 0;
            font-size: 11pt;
        }
        
        .info-periode strong {
            font-weight: bold;
        }
        
        /* Tabel Umum */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0 25px;
            font-size: 11pt;
            page-break-inside: auto;
        }
        
        /* Tabel Kepanitraan */
        .table-kepanitraan th {
            background-color: #d9e2f3 !important;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }
        
        /* Tabel Kesekretariatan */
        .table-kesekretariatan th {
            background-color: #e2f0d9 !important;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }
        
        /* Tabel Analisis - WARNA SESUAI BAGIAN */
        .table-analisis-kepanitraan th {
            background-color: #d9e2f3 !important;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }
        
        .table-analisis-kesekretariatan th {
            background-color: #e2f0d9 !important;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }
        
        /* Header tabel */
        th {
            border: 1px solid #000;
            padding: 8px 5px;
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
        }
        
        /* Data cells */
        td {
            border: 1px solid #000;
            padding: 7px 5px;
            vertical-align: top;
        }
        
        /* Kolom untuk tabel analisis (lebih lebar karena lebih sedikit kolom) */
        .table-analisis-kepanitraan .col-no,
        .table-analisis-kesekretariatan .col-no {
            width: 4%;
        }
        
        .table-analisis-kepanitraan .col-sasaran,
        .table-analisis-kesekretariatan .col-sasaran {
            width: 18%;
        }
        
        .table-analisis-kepanitraan .col-indikator,
        .table-analisis-kesekretariatan .col-indikator {
            width: 18%;
        }
        
        .table-analisis-kepanitraan .col-hambatan,
        .table-analisis-kesekretariatan .col-hambatan {
            width: 15%;
        }
        
        .table-analisis-kepanitraan .col-rekomendasi,
        .table-analisis-kesekretariatan .col-rekomendasi {
            width: 15%;
        }
        
        .table-analisis-kepanitraan .col-tindak-lanjut,
        .table-analisis-kesekretariatan .col-tindak-lanjut {
            width: 15%;
        }
        
        .table-analisis-kepanitraan .col-keberhasilan,
        .table-analisis-kesekretariatan .col-keberhasilan {
            width: 15%;
        }
        
        /* Nomor urut */
        .col-no {
            width: 4%;
            text-align: center;
        }
        
        /* Sasaran Strategis */
        .col-sasaran {
            width: 20%;
            text-align: left;
        }
        
        /* Indikator Kinerja */
        .col-indikator {
            width: 18%;
            text-align: left;
        }
        
        /* Kolom input dan realisasi */
        .col-input {
            width: 8%;
            text-align: center;
        }
        
        .col-realisasi {
            width: 8%;
            text-align: center;
        }
        
        .col-capaian {
            width: 8%;
            text-align: center;
        }
        
        /* Kolom analisis */
        .col-hambatan {
            width: 15%;
            text-align: left;
        }
        
        .col-rekomendasi {
            width: 15%;
            text-align: left;
        }
        
        .col-tindak-lanjut {
            width: 12%;
            text-align: left;
        }
        
        .col-keberhasilan {
            width: 12%;
            text-align: left;
        }
        
        /* Text alignment */
        .text-center {
            text-align: center;
        }
        
        .text-left {
            text-align: left;
        }
        
        .text-right {
            text-align: right;
        }
        
        /* Font untuk angka */
        .angka {
            font-family: Arial, sans-serif;
        }
        
        /* Format untuk nilai nihil */
        .nilai-nihil {
            color: #666;
            font-style: italic;
        }
        
        /* Baris dengan sasaran strategis yang sama */
        .group-sasaran td.sasaran-cell {
            border-top: 2px solid #000;
        }
        
        /* Baris pertama dalam grup sasaran */
        .first-in-group td {
            border-top: 2px solid #000;
        }
        
        /* Footer */
        .footer-laporan {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #000;
            font-size: 10pt;
            text-align: center;
        }
        
        .footer-info {
            text-align: left;
            margin-bottom: 20px;
        }
        
        .ttd-area {
            margin-top: 50px;
            text-align: center;
        }
        
        .ttd-box {
            display: inline-block;
            margin: 0 50px;
        }
        
        .ttd-space {
            height: 80px;
            margin-bottom: 10px;
        }
        
        .ttd-name {
            font-weight: bold;
            text-decoration: underline;
        }
        
        .ttd-position {
            font-size: 10pt;
        }
        
        /* Page break untuk print */
        .page-break {
            page-break-before: always;
        }
        
        /* Untuk judul bagian */
        .section-title {
            font-size: 13pt;
            font-weight: bold;
            margin: 25px 0 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #000;
            text-align: center;
            text-transform: uppercase;
        }
        
        /* Sub section title */
        .subsection-title {
            font-size: 12pt;
            font-weight: bold;
            margin: 20px 0 10px;
            padding-left: 10px;
            border-left: 4px solid #007bff;
        }
        
        /* Styling untuk capaian */
        .capaian-excellent {
            background-color: #d4edda;
            font-weight: bold;
        }
        
        .capaian-good {
            background-color: #fff3cd;
            font-weight: bold;
        }
        
        .capaian-fair {
            background-color: #f8d7da;
            font-weight: bold;
        }
        
        .capaian-na {
            background-color: #f5f5f5;
            color: #777;
        }
        
        /* Untuk cetakan */
        @media print {
            body {
                margin: 1.5cm;
            }
            
            .no-print {
                display: none;
            }
            
            table {
                page-break-inside: avoid;
            }
            
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            
            thead {
                display: table-header-group;
            }
        }
    </style>
</head>
<body>
    @php
        // Pisahkan data berdasarkan jenis (kepanitraan vs kesekretariatan)
        $jenisKepanitraan = ['perdata', 'pidana', 'tipikor', 'phi', 'hukum'];
        $jenisKesekretariatan = ['ptip', 'umum_keuangan', 'kepegawaian'];
        
        $dataKepanitraan = collect();
        $dataKesekretariatan = collect();
        
        foreach ($data as $item) {
            if (isset($item->jenis)) {
                if (in_array($item->jenis, $jenisKepanitraan)) {
                    $dataKepanitraan->push($item);
                } elseif (in_array($item->jenis, $jenisKesekretariatan)) {
                    $dataKesekretariatan->push($item);
                }
            }
        }
        
        // Kelompokkan berdasarkan sasaran strategis untuk setiap jenis
        $groupedKepanitraan = $dataKepanitraan->groupBy('sasaran_strategis')->sortBy(function($items, $key) {
            return $key;
        });
        
        $groupedKesekretariatan = $dataKesekretariatan->groupBy('sasaran_strategis')->sortBy(function($items, $key) {
            return $key;
        });
        
        // Data untuk analisis (gabungkan semua data dengan analisis)
        $dataWithAnalisis = $data->filter(function($item) {
            return !empty($item->hambatan) || !empty($item->rekomendasi) || 
                   !empty($item->tindak_lanjut) || !empty($item->keberhasilan);
        });
        
        // Kelompokkan data dengan analisis berdasarkan jenis
        $dataAnalisisKepanitraan = $dataWithAnalisis->filter(function($item) use ($jenisKepanitraan) {
            return in_array($item->jenis, $jenisKepanitraan);
        });
        
        $dataAnalisisKesekretariatan = $dataWithAnalisis->filter(function($item) use ($jenisKesekretariatan) {
            return in_array($item->jenis, $jenisKesekretariatan);
        });
        
        // Tentukan apakah ada data untuk masing-masing bagian
        $hasKepanitraan = $dataKepanitraan->count() > 0;
        $hasKesekretariatan = $dataKesekretariatan->count() > 0;
        $hasAnalisisKepanitraan = $dataAnalisisKepanitraan->count() > 0;
        $hasAnalisisKesekretariatan = $dataAnalisisKesekretariatan->count() > 0;
        
        // Mapping nama bagian
        $bagianMapping = [
            'perdata' => 'Perdata',
            'pidana' => 'Pidana',
            'tipikor' => 'Tipikor',
            'phi' => 'PHI',
            'hukum' => 'Hukum',
            'ptip' => 'PTIP',
            'umum_keuangan' => 'Umum & Keuangan',
            'kepegawaian' => 'Kepegawaian'
        ];
        
        // Nama triwulan
        $namaTriwulan = [
            '1' => 'I (Januari - Maret)',
            '2' => 'II (April - Juni)',
            '3' => 'III (Juli - September)',
            '4' => 'IV (Oktober - Desember)'
        ];
        
        // Fungsi helper untuk menentukan warna capaian
        function getAchievementClass($value) {
            if (!$value || $value == 0) return 'capaian-na';
            if ($value >= 90) return 'capaian-excellent';
            if ($value >= 70) return 'capaian-good';
            return 'capaian-fair';
        }
        
        // Fungsi helper untuk format angka
        function formatAngka($value) {
            if (is_numeric($value) && $value != 0) {
                return number_format($value, 0, ',', '.');
            }
            return '-';
        }
        
        // Fungsi helper untuk format persentase
        function formatPersen($value) {
            if (is_numeric($value)) {
                return number_format($value, 1, ',', '.') . '%';
            }
            return '-';
        }
        
        // Fungsi helper untuk format teks analisis
        function formatAnalisis($value) {
            if (!empty($value) && $value != '-') {
                return nl2br(e($value));
            }
            return '<span class="nilai-nihil">-</span>';
        }
        
        // Judul berdasarkan jenis laporan
        $judulLaporan = '';
        if ($jenisLaporan == 'bulanan') {
            $judulLaporan = 'LAPORAN BULANAN';
        } elseif ($jenisLaporan == 'tahunan') {
            $judulLaporan = 'LAPORAN TAHUNAN';
        } elseif ($jenisLaporan == 'triwulan') {
            $judulLaporan = 'LAPORAN TRIWULAN';
        }
    @endphp
    
    <!-- HEADER LAPORAN -->
    <div class="header-laporan">
        <h1>PENGADILAN NEGERI ...</h1>
        <h2>E-SIDAK (SISTEM INFORMASI DAFTAR KINERJA)</h2>
        <h3>{{ $judulLaporan }}</h3>
    </div>
    
    <!-- INFORMASI PERIODE -->
    <div class="info-periode">
        @if($jenisLaporan == 'bulanan' && $bulan && $tahun)
            <p><strong>Periode:</strong> Bulan {{ $namaBulan[$bulan] ?? $bulan }} Tahun {{ $tahun }}</p>
        @elseif($jenisLaporan == 'tahunan' && $tahun)
            <p><strong>Periode:</strong> Tahun {{ $tahun }}</p>
        @elseif($jenisLaporan == 'triwulan' && $triwulan && $tahun)
            <p><strong>Periode:</strong> Triwulan {{ $namaTriwulan[$triwulan] ?? $triwulan }} Tahun {{ $tahun }}</p>
        @endif
        
        @if($bagian && $bagian != 'all')
            <p><strong>Bagian:</strong> {{ $bagianMapping[$bagian] ?? ucfirst($bagian) }}</p>
        @else
            <p><strong>Bagian:</strong> Semua Bagian</p>
        @endif
        
        <p><strong>Tanggal Cetak:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
        <p><strong>Dicetak oleh:</strong> {{ $user->name }}</p>
    </div>
    
    <!-- LAPORAN KEPANITRAAN -->
    @if($hasKepanitraan)
    <div class="section-title">
        BAGIAN KEPANITRAAN
    </div>
    
    <!-- Tabel Kinerja Kepanitraan -->
    <table class="table-kepanitraan">
        <thead>
            <tr>
                <th rowspan="2" class="col-no">NO</th>
                <th rowspan="2" class="col-sasaran">SASARAN STRATEGIS</th>
                <th rowspan="2" class="col-indikator">INDIKATOR KINERJA</th>
                <th colspan="3">REALISASI</th>
                <th rowspan="2" class="col-capaian">CAPAIAN</th>
            </tr>
            <tr>
                <th class="col-input">INPUT 1</th>
                <th class="col-input">INPUT 2</th>
                <th class="col-realisasi">REALISASI</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $counter = 1;
                $previousSasaran = null;
            @endphp
            
            @foreach($groupedKepanitraan as $sasaran => $items)
                @php
                    $sortedItems = $items->sortBy(function($item) {
                        return $item->indikator_kinerja ?? '';
                    });
                    $isFirstInGroup = true;
                @endphp
                
                @foreach($sortedItems as $item)
                    <tr @if($isFirstInGroup && $previousSasaran !== null) class="first-in-group" @endif>
                        <td class="text-center angka">{{ $counter++ }}</td>
                        <td class="text-left sasaran-cell">
                            @if($isFirstInGroup)
                                {!! nl2br(e($sasaran)) !!}
                                @php $isFirstInGroup = false; @endphp
                            @endif
                        </td>
                        <td class="text-left">
                            @if(isset($item->indikator_kinerja) && !empty($item->indikator_kinerja))
                                {!! nl2br(e($item->indikator_kinerja)) !!}
                            @else
                                <span class="nilai-nihil">-</span>
                            @endif
                        </td>
                        <td class="text-center angka">
                            {{ formatAngka($item->input_1 ?? 0) }}
                        </td>
                        <td class="text-center angka">
                            {{ formatAngka($item->input_2 ?? 0) }}
                        </td>
                        <td class="text-center angka">
                            {{ formatAngka($item->realisasi ?? 0) }}
                        </td>
                        <td class="text-center angka {{ getAchievementClass($item->capaian ?? 0) }}">
                            {{ formatPersen($item->capaian ?? 0) }}
                        </td>
                    </tr>
                @endforeach
                @php $previousSasaran = $sasaran; @endphp
            @endforeach
        </tbody>
    </table>
    
    <!-- Analisis Kepanitraan -->
    @if($hasAnalisisKepanitraan)
    <div class="subsection-title">
        ANALISIS KEPANITRAAN
    </div>
    
    <table class="table-analisis-kepanitraan">
        <thead>
            <tr>
                <th class="col-no">NO</th>
                <th class="col-sasaran">SASARAN STRATEGIS</th>
                <th class="col-indikator">INDIKATOR KINERJA</th>
                <th class="col-hambatan">HAMBATAN</th>
                <th class="col-rekomendasi">REKOMENDASI</th>
                <th class="col-tindak-lanjut">TINDAK LANJUT</th>
                <th class="col-keberhasilan">KEBERHASILAN</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $counterAnalisis = 1;
            @endphp
            
            @foreach($dataAnalisisKepanitraan->sortBy('sasaran_strategis') as $item)
                <tr>
                    <td class="text-center angka">{{ $counterAnalisis++ }}</td>
                    <td class="text-left">{!! nl2br(e($item->sasaran_strategis)) !!}</td>
                    <td class="text-left">
                        @if(isset($item->indikator_kinerja) && !empty($item->indikator_kinerja))
                            {!! nl2br(e($item->indikator_kinerja)) !!}
                        @else
                            <span class="nilai-nihil">-</span>
                        @endif
                    </td>
                    <td class="text-left">{!! formatAnalisis($item->hambatan ?? '') !!}</td>
                    <td class="text-left">{!! formatAnalisis($item->rekomendasi ?? '') !!}</td>
                    <td class="text-left">{!! formatAnalisis($item->tindak_lanjut ?? '') !!}</td>
                    <td class="text-left">{!! formatAnalisis($item->keberhasilan ?? '') !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    @endif
    
    <!-- LAPORAN KESKRETARIATAN -->
    @if($hasKesekretariatan)
    @if($hasKepanitraan)
    <div class="page-break"></div>
    @endif
    
    <div class="section-title">
        BAGIAN KESKRETARIATAN
    </div>
    
    <!-- Tabel Kinerja Kesekretariatan -->
    <table class="table-kesekretariatan">
        <thead>
            <tr>
                <th class="col-no">NO</th>
                <th class="col-sasaran">SASARAN STRATEGIS</th>
                <th class="col-indikator">INDIKATOR KINERJA</th>
                <th class="col-realisasi">REALISASI</th>
                <th class="col-capaian">CAPAIAN</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $counter = 1;
                $previousSasaran = null;
            @endphp
            
            @foreach($groupedKesekretariatan as $sasaran => $items)
                @php
                    $sortedItems = $items->sortBy(function($item) {
                        return $item->indikator_kinerja ?? '';
                    });
                    $isFirstInGroup = true;
                @endphp
                
                @foreach($sortedItems as $item)
                    <tr @if($isFirstInGroup && $previousSasaran !== null) class="first-in-group" @endif>
                        <td class="text-center angka">{{ $counter++ }}</td>
                        <td class="text-left sasaran-cell">
                            @if($isFirstInGroup)
                                {!! nl2br(e($sasaran)) !!}
                                @php $isFirstInGroup = false; @endphp
                            @endif
                        </td>
                        <td class="text-left">
                            @if(isset($item->indikator_kinerja) && !empty($item->indikator_kinerja))
                                {!! nl2br(e($item->indikator_kinerja)) !!}
                            @else
                                <span class="nilai-nihil">-</span>
                            @endif
                        </td>
                        <td class="text-center angka">
                            {{ formatAngka($item->realisasi ?? 0) }}
                        </td>
                        <td class="text-center angka {{ getAchievementClass($item->capaian ?? 0) }}">
                            {{ formatPersen($item->capaian ?? 0) }}
                        </td>
                    </tr>
                @endforeach
                @php $previousSasaran = $sasaran; @endphp
            @endforeach
        </tbody>
    </table>
    
    <!-- Analisis Kesekretariatan -->
    @if($hasAnalisisKesekretariatan)
    <div class="subsection-title">
        ANALISIS KESKRETARIATAN
    </div>
    
    <table class="table-analisis-kesekretariatan">
        <thead>
            <tr>
                <th class="col-no">NO</th>
                <th class="col-sasaran">SASARAN STRATEGIS</th>
                <th class="col-indikator">INDIKATOR KINERJA</th>
                <th class="col-hambatan">HAMBATAN</th>
                <th class="col-rekomendasi">REKOMENDASI</th>
                <th class="col-tindak-lanjut">TINDAK LANJUT</th>
                <th class="col-keberhasilan">KEBERHASILAN</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $counterAnalisis = 1;
            @endphp
            
            @foreach($dataAnalisisKesekretariatan->sortBy('sasaran_strategis') as $item)
                <tr>
                    <td class="text-center angka">{{ $counterAnalisis++ }}</td>
                    <td class="text-left">{!! nl2br(e($item->sasaran_strategis)) !!}</td>
                    <td class="text-left">
                        @if(isset($item->indikator_kinerja) && !empty($item->indikator_kinerja))
                            {!! nl2br(e($item->indikator_kinerja)) !!}
                        @else
                            <span class="nilai-nihil">-</span>
                        @endif
                    </td>
                    <td class="text-left">{!! formatAnalisis($item->hambatan ?? '') !!}</td>
                    <td class="text-left">{!! formatAnalisis($item->rekomendasi ?? '') !!}</td>
                    <td class="text-left">{!! formatAnalisis($item->tindak_lanjut ?? '') !!}</td>
                    <td class="text-left">{!! formatAnalisis($item->keberhasilan ?? '') !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    @endif
    
    @if(!$hasKepanitraan && !$hasKesekretariatan)
    <div style="text-align: center; padding: 50px 0; color: #666;">
        <h3>TIDAK ADA DATA DITEMUKAN</h3>
        <p>Tidak ada data yang tersedia untuk periode yang dipilih.</p>
    </div>
    @endif
    
    <!-- FOOTER DAN TANDA TANGAN -->
    <div class="footer-laporan">
        @if($hasKepanitraan || $hasKesekretariatan)
        <div class="footer-info">
            <p><strong>Keterangan:</strong></p>
            <p>1. Input 1 dan Input 2 adalah komponen perhitungan untuk indikator kinerja tertentu</p>
            <p>2. Realisasi adalah hasil aktual yang dicapai</p>
            <p>3. Capaian dihitung berdasarkan perbandingan antara realisasi dengan target</p>
            <p>4. Warna hijau: capaian ≥ 90%, kuning: 70-89%, merah: < 70%, abu-abu: tidak ada data</p>
            <p>5. Analisis meliputi hambatan, rekomendasi, tindak lanjut, dan keberhasilan yang dicapai</p>
        </div>
        
        <div class="ttd-area">
            <div class="ttd-box">
                <div class="ttd-space"></div>
                <div class="ttd-name">{{ $user->name }}</div>
                <div class="ttd-position">Operator E-SIDAK</div>
            </div>
            
            <div class="ttd-box">
                <div class="ttd-space"></div>
                <div class="ttd-name">[Nama Kepala Bagian]</div>
                <div class="ttd-position">Kepala Bagian</div>
            </div>
        </div>
        @endif
        
        <div style="margin-top: 30px; font-size: 9pt; color: #666;">
            <p>Dicetak dari Sistem E-SIDAK Pengadilan Negeri ...</p>
            <p>Alamat: ... | Telp: ... | Email: ...</p>
        </div>
    </div>
</body>
</html>