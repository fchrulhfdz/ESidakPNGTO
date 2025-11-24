@extends('layouts.app')

@section('title', 'Cetak Laporan - E-SIDAK')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Cetak Laporan E-SIDAK</h1>

    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form action="{{ route('laporan') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <select name="bulan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Bulan</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <select name="tahun" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Tahun</option>
                        @for($year = date('Y'); $year >= 2020; $year--)
                            <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bagian</label>
                    <select name="bagian" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all" {{ request('bagian') == 'all' ? 'selected' : '' }}>Semua Bagian</option>
                        <optgroup label="Perkara">
                            <option value="perdata" {{ request('bagian') == 'perdata' ? 'selected' : '' }}>Perdata</option>
                            <option value="pidana" {{ request('bagian') == 'pidana' ? 'selected' : '' }}>Pidana</option>
                            <option value="tipikor" {{ request('bagian') == 'tipikor' ? 'selected' : '' }}>Tipikor</option>
                            <option value="phi" {{ request('bagian') == 'phi' ? 'selected' : '' }}>PHI</option>
                            <option value="hukum" {{ request('bagian') == 'hukum' ? 'selected' : '' }}>Hukum</option>
                        </optgroup>
                        <optgroup label="Kesekretariatan">
                            <option value="ptip" {{ request('bagian') == 'ptip' ? 'selected' : '' }}>PTIP</option>
                            <option value="umum_keuangan" {{ request('bagian') == 'umum_keuangan' ? 'selected' : '' }}>Umum & Keuangan</option>
                            <option value="kepegawaian" {{ request('bagian') == 'kepegawaian' ? 'selected' : '' }}>Kepegawaian</option>
                        </optgroup>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" name="search" value="1"
                            class="w-full bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition duration-200">
                        CARI
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tombol Cetak PDF -->
    @if(request()->has('search'))
    <div class="mb-4 flex justify-end">
        <a href="{{ route('laporan.cetak-pdf', request()->query()) }}" 
           class="bg-red-500 text-white px-6 py-2 rounded-md hover:bg-red-600 transition duration-200 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            CETAK PDF
        </a>
    </div>
    @endif

    <!-- Tabel Laporan -->
    @if(request()->has('search'))
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bagian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sasaran Strategis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Indikator Kinerja</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Input</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Realisasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capaian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($data as $index => $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(isset($item->jenis))
                                @php
                                    $jenisMapping = [
                                        'perdata' => 'Perdata',
                                        'pidana' => 'Pidana', 
                                        'tipikor' => 'Tipikor',
                                        'phi' => 'PHI',
                                        'hukum' => 'Hukum',
                                        'ptip' => 'PTIP',
                                        'umum_keuangan' => 'Umum & Keuangan',
                                        'kepegawaian' => 'Kepegawaian'
                                    ];
                                @endphp
                                {{ $jenisMapping[$item->jenis] ?? ucfirst($item->jenis) }}
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $item->sasaran_strategis }}</td>
                        <td class="px-6 py-4">
                            @if(isset($item->indikator_kinerja))
                                {{ $item->indikator_kinerja }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ number_format($item->target, 2) }}%</td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <span class="font-medium">Input 1:</span> {{ $item->input_1 }}<br>
                                <span class="font-medium">Input 2:</span> {{ $item->input_2 }}
                            </div>
                        </td>
                        <td class="px-6 py-4">{{ number_format($item->realisasi, 2) }}%</td>
                        <td class="px-6 py-4">{{ number_format($item->capaian, 2) }}%</td>
                        <td class="px-6 py-4">{{ $item->tanggal->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                    @if($data->count() === 0)
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center py-8">
                                <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-lg font-medium text-gray-600">Tidak ada data yang ditemukan</p>
                                <p class="text-gray-500 mt-2">Coba ubah filter pencarian Anda</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if($data->count() > 0)
        <div class="px-6 py-4 border-t border-gray-200">
            <p class="text-sm text-gray-600">
                Menampilkan <span class="font-medium">{{ $data->count() }}</span> data
                @if(request('bulan') || request('tahun') || request('bagian') != 'all')
                    dengan filter:
                    @if(request('bulan')) Bulan {{ DateTime::createFromFormat('!m', request('bulan'))->format('F') }} @endif
                    @if(request('tahun')) Tahun {{ request('tahun') }} @endif
                    @if(request('bagian') != 'all') 
                        Bagian 
                        @php
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
                        @endphp
                        {{ $bagianMapping[request('bagian')] ?? request('bagian') }}
                    @endif
                @endif
            </p>
        </div>
        @endif
    </div>
    @else
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <h3 class="text-xl font-medium text-gray-600 mb-2">Pilih Filter Laporan</h3>
        <p class="text-gray-500">Gunakan form di atas untuk memfilter data laporan yang ingin ditampilkan.</p>
    </div>
    @endif
</div>
@endsection