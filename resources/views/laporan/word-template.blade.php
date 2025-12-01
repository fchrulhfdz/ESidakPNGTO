<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Laporan E-SIDAK</title>
    <style>
        /* Styles for Word Document */
        @page {
            size: A4 landscape;
            margin: 2cm;
        }
        
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        
        .title {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .subtitle {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .info-box {
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }
        
        .info-line {
            margin: 5px 0;
        }
        
        .document-info {
            margin-bottom: 20px;
            font-size: 10pt;
            color: #666;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 9pt;
            table-layout: fixed;
        }
        
        th {
            background-color: #f2f2f2;
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
            font-weight: bold;
            vertical-align: middle;
        }
        
        td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }
        
        .text-center {
            text-align: center;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9pt;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            font-style: italic;
            color: #999;
        }
        
        /* Column widths */
        .col-no { width: 5%; }
        .col-bagian { width: 12%; }
        .col-sasaran { width: 20%; }
        .col-indikator { width: 18%; }
        .col-target { width: 8%; }
        .col-input { width: 15%; }
        .col-realisasi { width: 8%; }
        .col-capaian { width: 8%; }
        .col-tanggal { width: 6%; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="title">LAPORAN MONITORING KINERJA</div>
        <div class="subtitle">E-SIDAK - SISTEM INFORMASI DUA ARAH KINERJA</div>
        <div>PENGADILAN NEGERI GORONTALO</div>
    </div>
    
    <!-- Document Info -->
    <div class="document-info">
        <div class="info-line"><strong>Tanggal Cetak:</strong> {{ date('d/m/Y H:i:s') }}</div>
        <div class="info-line"><strong>{{ $periodeText }}</strong></div>
        <div class="info-line"><strong>Jenis Laporan:</strong> {{ ucfirst($jenisLaporan) }}</div>
        @if($user->role === 'super_admin')
            <div class="info-line"><strong>Laporan:</strong> Semua Bagian</div>
        @else
            <div class="info-line"><strong>Bagian:</strong> {{ $bagianMapping[$user->role] ?? ucfirst($user->role) }}</div>
        @endif
    </div>
    
    <!-- Table Data -->
    @if($data->count() > 0)
    <table>
        <thead>
            <tr>
                <th class="col-no">No</th>
                @if($user->role === 'super_admin')
                <th class="col-bagian">Bagian</th>
                @endif
                <th class="col-sasaran">Sasaran Strategis</th>
                <th class="col-indikator">Indikator Kinerja</th>
                <th class="col-target">Target</th>
                <th class="col-input">Input</th>
                <th class="col-realisasi">Realisasi</th>
                <th class="col-capaian">Capaian</th>
                <th class="col-tanggal">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                @if($user->role === 'super_admin')
                <td>{{ $bagianMapping[$item->jenis] ?? ucfirst($item->jenis) }}</td>
                @endif
                <td>{{ $item->sasaran_strategis }}</td>
                <td>{{ $item->indikator_kinerja ?? '-' }}</td>
                <td class="text-center">{{ number_format($item->target, 2) }}%</td>
                <td>
                    <strong>Input 1:</strong> {{ $item->input_1 }}<br>
                    <strong>Input 2:</strong> {{ $item->input_2 }}
                </td>
                <td class="text-center">{{ number_format($item->realisasi, 2) }}%</td>
                <td class="text-center">{{ number_format($item->capaian, 2) }}%</td>
                <td class="text-center">{{ $item->tanggal->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="info-box">
        <strong>Total Data:</strong> {{ $data->count() }} record
    </div>
    @else
    <div class="no-data">
        <p>Tidak ada data yang ditemukan untuk periode yang dipilih.</p>
    </div>
    @endif
    
    <!-- Footer -->
    <div class="footer">
        <p>Dicetak oleh: {{ $user->name }} ({{ $user->email }})</p>
        <p>E-SIDAK © {{ date('Y') }} - Pengadilan Negeri Gorontalo</p>
        <p>Halaman 1 dari 1</p>
    </div>
</body>
</html>