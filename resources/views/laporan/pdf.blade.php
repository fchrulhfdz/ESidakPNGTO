<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan E-SIDAK - Pengadilan Negeri Gorontalo</title>
    <style>
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 14px;
        }
        .header p {
            margin: 5px 0;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .page-break {
            page-break-after: always;
        }
        .text-center {
            text-align: center;
        }
        .no-data {
            text-align: center;
            font-style: italic;
            padding: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PENGADILAN NEGERI GORONTALO</h1>
        <h2>LAPORAN E-SIDAK (E-SISTEM INFORMASI PEMANTAUAN KINERJA)</h2>
        <p>
            Periode: 
            @if($bulan)
                {{ DateTime::createFromFormat('!m', $bulan)->format('F') }}
            @else
                Semua Bulan
            @endif
            {{ $tahun }}
            @if($bagian != 'all')
                - Bagian: {{ ucfirst(str_replace('_', ' ', $bagian)) }}
            @endif
        </p>
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    @if($data->count() > 0)
        <table>
            <thead>
                <tr>
                    <th width="30">No</th>
                    <th width="80">Bagian</th>
                    <th width="150">Sasaran Strategis</th>
                    <th width="120">Indikator Kinerja</th>
                    <th width="50">Target</th>
                    <th width="80">Input 1</th>
                    <th width="80">Input 2</th>
                    <th width="60">Realisasi</th>
                    <th width="60">Capaian</th>
                    <th width="70">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $jenisMapping = [
                        'perdata' => 'Perdata',
                        'pidana' => 'Pidana',
                        'tipikor' => 'Tipikor',
                        'phi' => 'PHI',
                        'hukum' => 'Hukum',
                        'ptip' => 'PTIP',
                        'umum_keuangan' => 'Umum & Keu',
                        'kepegawaian' => 'Kepegawaian'
                    ];
                @endphp
                @foreach($data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $jenisMapping[$item->jenis] ?? $item->jenis }}</td>
                    <td>{{ $item->sasaran_strategis }}</td>
                    <td>
                        @if(isset($item->indikator_kinerja))
                            {{ $item->indikator_kinerja }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">{{ number_format($item->target, 2) }}%</td>
                    <td class="text-center">{{ $item->input_1 }}</td>
                    <td class="text-center">{{ $item->input_2 }}</td>
                    <td class="text-center">{{ number_format($item->realisasi, 2) }}%</td>
                    <td class="text-center">{{ number_format($item->capaian, 2) }}%</td>
                    <td class="text-center">{{ $item->tanggal->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>Total Data: {{ $data->count() }} record</p>
            <p>Dicetak oleh: {{ auth()->user()->name }} ({{ auth()->user()->email }})</p>
            <p>Pengadilan Negeri Gorontalo - E-SIDAK</p>
        </div>
    @else
        <div class="no-data">
            <p>Tidak ada data yang ditemukan untuk periode yang dipilih.</p>
            <p>Filter: 
                @if($bulan) Bulan {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} @endif
                @if($tahun) Tahun {{ $tahun }} @endif
                @if($bagian != 'all') Bagian {{ ucfirst(str_replace('_', ' ', $bagian)) }} @endif
            </p>
        </div>
    @endif
</body>
</html>