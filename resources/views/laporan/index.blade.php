@extends('layouts.app')

@section('title', 'Cetak Laporan - E-SIDAK')

@section('content')
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
    
    $namaBulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];
@endphp

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Cetak Laporan E-SIDAK</h1>

    <!-- Tab Navigation -->
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button type="button" 
                        onclick="switchTab('bulanan')"
                        class="{{ $jenisLaporan == 'bulanan' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition duration-150 ease-in-out">
                    Laporan Bulanan
                </button>
                <button type="button" 
                        onclick="switchTab('tahunan')"
                        class="{{ $jenisLaporan == 'tahunan' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition duration-150 ease-in-out">
                    Laporan Tahunan
                </button>
                <button type="button" 
                        onclick="switchTab('triwulan')"
                        class="{{ $jenisLaporan == 'triwulan' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition duration-150 ease-in-out">
                    Laporan Triwulan
                </button>
            </nav>
        </div>
    </div>

    <!-- Form untuk setiap tab -->
    <div class="space-y-6">
        <!-- Form Bulanan -->
        <div id="form-bulanan" class="tab-content {{ $jenisLaporan == 'bulanan' ? 'block' : 'hidden' }}">
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('laporan') }}" method="GET">
                    <input type="hidden" name="jenis_laporan" value="bulanan">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                            <select name="bulan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Bulan</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('bulan') == $i && $jenisLaporan == 'bulanan' ? 'selected' : '' }}>
                                        {{ $namaBulan[$i] }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <select name="tahun" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Tahun</option>
                        @for($year = 2030; $year >= 2025; $year--)
                            <option value="{{ $year }}" {{ request('tahun') == $year && $jenisLaporan == 'tahunan' ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                            </select>
                        </div>
                        
                        <!-- Bagian dropdown untuk super_admin -->
                        @if(auth()->user()->role === 'super_admin')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bagian</label>
                            <select name="bagian" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="all" {{ request('bagian') == 'all' && $jenisLaporan == 'bulanan' ? 'selected' : '' }}>Semua Bagian</option>
                                <optgroup label="Perkara">
                                    <option value="perdata" {{ request('bagian') == 'perdata' && $jenisLaporan == 'bulanan' ? 'selected' : '' }}>Perdata</option>
                                    <option value="pidana" {{ request('bagian') == 'pidana' && $jenisLaporan == 'bulanan' ? 'selected' : '' }}>Pidana</option>
                                    <option value="tipikor" {{ request('bagian') == 'tipikor' && $jenisLaporan == 'bulanan' ? 'selected' : '' }}>Tipikor</option>
                                    <option value="phi" {{ request('bagian') == 'phi' && $jenisLaporan == 'bulanan' ? 'selected' : '' }}>PHI</option>
                                    <option value="hukum" {{ request('bagian') == 'hukum' && $jenisLaporan == 'bulanan' ? 'selected' : '' }}>Hukum</option>
                                </optgroup>
                                <optgroup label="Kesekretariatan">
                                    <option value="ptip" {{ request('bagian') == 'ptip' && $jenisLaporan == 'bulanan' ? 'selected' : '' }}>PTIP</option>
                                    <option value="umum_keuangan" {{ request('bagian') == 'umum_keuangan' && $jenisLaporan == 'bulanan' ? 'selected' : '' }}>Umum & Keuangan</option>
                                    <option value="kepegawaian" {{ request('bagian') == 'kepegawaian' && $jenisLaporan == 'bulanan' ? 'selected' : '' }}>Kepegawaian</option>
                                </optgroup>
                            </select>
                        </div>
                        @else
                        <input type="hidden" name="bagian" value="{{ auth()->user()->role }}">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bagian</label>
                            <div class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                                {{ $bagianMapping[auth()->user()->role] ?? ucfirst(auth()->user()->role) }}
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Anda hanya dapat melihat laporan bagian Anda</p>
                        </div>
                        @endif
                        
                        <div class="flex items-end">
                            <button type="submit" name="search" value="1"
                                    class="w-full bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition duration-200">
                                CARI
                            </button>
                        </div>
                        
                        @if(request()->has('search') && $jenisLaporan == 'bulanan' && $data->count() > 0)
                        <div class="flex items-end">
                            <a href="{{ route('laporan.cetak-word', array_merge(request()->query(), ['jenis_laporan' => 'bulanan'])) }}" 
                               class="w-full bg-red-500 text-white px-6 py-2 rounded-md hover:bg-red-600 transition duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                CETAK WORD
                            </a>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Form Tahunan -->
        <div id="form-tahunan" class="tab-content {{ $jenisLaporan == 'tahunan' ? 'block' : 'hidden' }}">
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('laporan') }}" method="GET">
            <input type="hidden" name="jenis_laporan" value="tahunan">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <select name="tahun" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Tahun</option>
                        @for($year = 2030; $year >= 2025; $year--)
                            <option value="{{ $year }}" {{ request('tahun') == $year && $jenisLaporan == 'tahunan' ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                </div>
                        
                        <!-- Bagian dropdown untuk super_admin -->
                        @if(auth()->user()->role === 'super_admin')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bagian</label>
                            <select name="bagian" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="all" {{ request('bagian') == 'all' && $jenisLaporan == 'tahunan' ? 'selected' : '' }}>Semua Bagian</option>
                                <optgroup label="Perkara">
                                    <option value="perdata" {{ request('bagian') == 'perdata' && $jenisLaporan == 'tahunan' ? 'selected' : '' }}>Perdata</option>
                                    <option value="pidana" {{ request('bagian') == 'pidana' && $jenisLaporan == 'tahunan' ? 'selected' : '' }}>Pidana</option>
                                    <option value="tipikor" {{ request('bagian') == 'tipikor' && $jenisLaporan == 'tahunan' ? 'selected' : '' }}>Tipikor</option>
                                    <option value="phi" {{ request('bagian') == 'phi' && $jenisLaporan == 'tahunan' ? 'selected' : '' }}>PHI</option>
                                    <option value="hukum" {{ request('bagian') == 'hukum' && $jenisLaporan == 'tahunan' ? 'selected' : '' }}>Hukum</option>
                                </optgroup>
                                <optgroup label="Kesekretariatan">
                                    <option value="ptip" {{ request('bagian') == 'ptip' && $jenisLaporan == 'tahunan' ? 'selected' : '' }}>PTIP</option>
                                    <option value="umum_keuangan" {{ request('bagian') == 'umum_keuangan' && $jenisLaporan == 'tahunan' ? 'selected' : '' }}>Umum & Keuangan</option>
                                    <option value="kepegawaian" {{ request('bagian') == 'kepegawaian' && $jenisLaporan == 'tahunan' ? 'selected' : '' }}>Kepegawaian</option>
                                </optgroup>
                            </select>
                        </div>
                        @else
                        <input type="hidden" name="bagian" value="{{ auth()->user()->role }}">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bagian</label>
                            <div class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                                {{ $bagianMapping[auth()->user()->role] ?? ucfirst(auth()->user()->role) }}
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Anda hanya dapat melihat laporan bagian Anda</p>
                        </div>
                        @endif
                        
                        <div class="flex items-end">
                            <button type="submit" name="search" value="1"
                                    class="w-full bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition duration-200">
                                CARI
                            </button>
                        </div>
                        
                        @if(request()->has('search') && $jenisLaporan == 'tahunan' && $data->count() > 0)
                        <div class="flex items-end">
                            <a href="{{ route('laporan.cetak-word', array_merge(request()->query(), ['jenis_laporan' => 'tahunan'])) }}" 
                               class="w-full bg-red-500 text-white px-6 py-2 rounded-md hover:bg-red-600 transition duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                CETAK WORD
                            </a>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Form Triwulan -->
        <div id="form-triwulan" class="tab-content {{ $jenisLaporan == 'triwulan' ? 'block' : 'hidden' }}">
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('laporan') }}" method="GET">
                    <input type="hidden" name="jenis_laporan" value="triwulan">
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Triwulan</label>
                            <select name="triwulan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Triwulan</option>
                                <option value="1" {{ request('triwulan') == 1 && $jenisLaporan == 'triwulan' ? 'selected' : '' }}>Triwulan 1 (Januari-Maret)</option>
                                <option value="2" {{ request('triwulan') == 2 && $jenisLaporan == 'triwulan' ? 'selected' : '' }}>Triwulan 2 (April-Juni)</option>
                                <option value="3" {{ request('triwulan') == 3 && $jenisLaporan == 'triwulan' ? 'selected' : '' }}>Triwulan 3 (Juli-September)</option>
                                <option value="4" {{ request('triwulan') == 4 && $jenisLaporan == 'triwulan' ? 'selected' : '' }}>Triwulan 4 (Oktober-Desember)</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <select name="tahun" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Tahun</option>
                        @for($year = 2030; $year >= 2025; $year--)
                            <option value="{{ $year }}" {{ request('tahun') == $year && $jenisLaporan == 'tahunan' ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                            </select>
                        </div>
                        
                        <!-- Bagian dropdown untuk super_admin -->
                        @if(auth()->user()->role === 'super_admin')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bagian</label>
                            <select name="bagian" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="all" {{ request('bagian') == 'all' && $jenisLaporan == 'triwulan' ? 'selected' : '' }}>Semua Bagian</option>
                                <optgroup label="Perkara">
                                    <option value="perdata" {{ request('bagian') == 'perdata' && $jenisLaporan == 'triwulan' ? 'selected' : '' }}>Perdata</option>
                                    <option value="pidana" {{ request('bagian') == 'pidana' && $jenisLaporan == 'triwulan' ? 'selected' : '' }}>Pidana</option>
                                    <option value="tipikor" {{ request('bagian') == 'tipikor' && $jenisLaporan == 'triwulan' ? 'selected' : '' }}>Tipikor</option>
                                    <option value="phi" {{ request('bagian') == 'phi' && $jenisLaporan == 'triwulan' ? 'selected' : '' }}>PHI</option>
                                    <option value="hukum" {{ request('bagian') == 'hukum' && $jenisLaporan == 'triwulan' ? 'selected' : '' }}>Hukum</option>
                                </optgroup>
                                <optgroup label="Kesekretariatan">
                                    <option value="ptip" {{ request('bagian') == 'ptip' && $jenisLaporan == 'triwulan' ? 'selected' : '' }}>PTIP</option>
                                    <option value="umum_keuangan" {{ request('bagian') == 'umum_keuangan' && $jenisLaporan == 'triwulan' ? 'selected' : '' }}>Umum & Keuangan</option>
                                    <option value="kepegawaian" {{ request('bagian') == 'kepegawaian' && $jenisLaporan == 'triwulan' ? 'selected' : '' }}>Kepegawaian</option>
                                </optgroup>
                            </select>
                        </div>
                        @else
                        <input type="hidden" name="bagian" value="{{ auth()->user()->role }}">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bagian</label>
                            <div class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100">
                                {{ $bagianMapping[auth()->user()->role] ?? ucfirst(auth()->user()->role) }}
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Anda hanya dapat melihat laporan bagian Anda</p>
                        </div>
                        @endif
                        
                        <div class="flex items-end">
                            <button type="submit" name="search" value="1"
                                    class="w-full bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition duration-200">
                                CARI
                            </button>
                        </div>
                        
                        @if(request()->has('search') && $jenisLaporan == 'triwulan' && $data->count() > 0)
                        <div class="flex items-end">
                            <a href="{{ route('laporan.cetak-word', array_merge(request()->query(), ['jenis_laporan' => 'triwulan'])) }}" 
                               class="w-full bg-red-500 text-white px-6 py-2 rounded-md hover:bg-red-600 transition duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                CETAK WORD
                            </a>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Info Role User -->
    @if(auth()->user()->role !== 'super_admin')
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-blue-700">
                Anda login sebagai <strong>{{ $bagianMapping[auth()->user()->role] ?? ucfirst(auth()->user()->role) }}</strong>. 
                Hanya dapat melihat laporan bagian Anda sendiri.
            </p>
        </div>
    </div>
    @else
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <p class="text-green-700">
                Anda login sebagai <strong>Super Admin</strong>. 
                Dapat melihat laporan dari semua bagian.
            </p>
        </div>
    </div>
    @endif

    <!-- Informasi Filter Aktif -->
    @if(request()->has('search'))
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-medium text-gray-700">Filter Aktif:</h3>
                <div class="mt-1 flex flex-wrap gap-2">
                    @if($jenisLaporan == 'bulanan')
                        @if(request('bulan') && request('tahun'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Bulan: {{ $namaBulan[request('bulan')] }}
                            </span>
                        @endif
                    @elseif($jenisLaporan == 'tahunan')
                        @if(request('tahun'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Tahun: {{ request('tahun') }}
                            </span>
                        @endif
                    @elseif($jenisLaporan == 'triwulan')
                        @if(request('triwulan') && request('tahun'))
                            @php
                                $namaTriwulan = [
                                    '1' => 'I (Januari-Maret)',
                                    '2' => 'II (April-Juni)',
                                    '3' => 'III (Juli-September)',
                                    '4' => 'IV (Oktober-Desember)'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                Triwulan: {{ $namaTriwulan[request('triwulan')] ?? request('triwulan') }}
                            </span>
                        @endif
                    @endif
                    
                    @if(request('tahun'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Tahun: {{ request('tahun') }}
                        </span>
                    @endif
                    
                    @if(auth()->user()->role === 'super_admin' && request('bagian') && request('bagian') != 'all')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Bagian: {{ $bagianMapping[request('bagian')] ?? request('bagian') }}
                        </span>
                    @endif
                    
                    @if(auth()->user()->role === 'super_admin' && (!request('bagian') || request('bagian') == 'all'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Semua Bagian
                        </span>
                    @endif
                </div>
            </div>
            
            @if($data->count() > 0)
            <div class="text-right">
                <p class="text-sm text-gray-600">Total Data: <span class="font-bold">{{ $data->count() }}</span></p>
                <p class="text-xs text-gray-500">Jenis Laporan: {{ ucfirst($jenisLaporan) }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Tabel Laporan -->
    @if(request()->has('search') && $data->count() > 0)
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        @if(auth()->user()->role === 'super_admin')
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bagian</th>
                        @endif
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
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $index + 1 }}</td>
                        @if(auth()->user()->role === 'super_admin')
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
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $item->jenis == 'perdata' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $item->jenis == 'pidana' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $item->jenis == 'tipikor' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $item->jenis == 'phi' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $item->jenis == 'hukum' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $item->jenis == 'ptip' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                    {{ $item->jenis == 'umum_keuangan' ? 'bg-pink-100 text-pink-800' : '' }}
                                    {{ $item->jenis == 'kepegawaian' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $jenisMapping[$item->jenis] ?? ucfirst($item->jenis) }}
                                </span>
                            @endif
                        </td>
                        @endif
                        <td class="px-6 py-4">{{ $item->sasaran_strategis }}</td>
                        <td class="px-6 py-4">
                            @if(isset($item->indikator_kinerja))
                                {{ $item->indikator_kinerja }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center font-medium {{ $item->target >= 90 ? 'text-green-600' : ($item->target >= 70 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ number_format($item->target, 2) }}%
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <div class="mb-1">
                                    <span class="font-medium text-gray-600">Input 1:</span> 
                                    <span class="text-gray-800">{{ $item->input_1 }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-600">Input 2:</span> 
                                    <span class="text-gray-800">{{ $item->input_2 }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center font-medium {{ $item->realisasi >= 90 ? 'text-green-600' : ($item->realisasi >= 70 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ number_format($item->realisasi, 2) }}%
                        </td>
                        <td class="px-6 py-4 text-center font-bold {{ $item->capaian >= 90 ? 'text-green-600' : ($item->capaian >= 70 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ number_format($item->capaian, 2) }}%
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                                {{ $item->tanggal->format('d/m/Y') }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-600">
                    Menampilkan <span class="font-bold">{{ $data->count() }}</span> data
                </p>
                <div class="flex space-x-2">
                    <a href="{{ route('laporan.cetak-word', array_merge(request()->query(), ['jenis_laporan' => $jenisLaporan])) }}" 
                       class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        CETAK WORD
                    </a>
                </div>
            </div>
        </div>
    </div>
    @elseif(request()->has('search') && $data->count() === 0)
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <svg class="w-20 h-20 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <h3 class="text-xl font-medium text-gray-600 mb-2">Data Tidak Ditemukan</h3>
        <p class="text-gray-500 mb-4">Tidak ada data yang cocok dengan filter yang Anda pilih.</p>
        <p class="text-sm text-blue-500 font-medium">Coba ubah filter pencarian Anda atau pilih jenis laporan lain.</p>
    </div>
    @else
    <div class="bg-white rounded-lg shadow-md p-12 text-center">
        <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <h3 class="text-xl font-medium text-gray-600 mb-2">Pilih Filter Laporan</h3>
        <p class="text-gray-500 mb-4">Gunakan form di atas untuk memfilter data laporan yang ingin ditampilkan.</p>
        <div class="space-y-2">
            @if(auth()->user()->role !== 'super_admin')
            <p class="text-blue-500 font-medium">Anda login sebagai <strong>{{ $bagianMapping[auth()->user()->role] ?? ucfirst(auth()->user()->role) }}</strong></p>
            <p class="text-sm text-gray-500">Hanya dapat melihat laporan bagian Anda sendiri.</p>
            @else
            <p class="text-green-500 font-medium">Anda login sebagai <strong>Super Admin</strong></p>
            <p class="text-sm text-gray-500">Dapat melihat laporan dari semua bagian.</p>
            @endif
        </div>
    </div>
    @endif
</div>

<script>
function switchTab(tab) {
    // Update URL dengan parameter tab
    const url = new URL(window.location.href);
    url.searchParams.set('jenis_laporan', tab);
    url.searchParams.delete('search');
    url.searchParams.delete('bulan');
    url.searchParams.delete('tahun');
    url.searchParams.delete('bagian');
    url.searchParams.delete('triwulan');
    
    window.location.href = url.toString();
}

// Set tab aktif berdasarkan URL saat page load
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const jenisLaporan = urlParams.get('jenis_laporan') || 'bulanan';
    
    // Hide all tabs first
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Show active tab
    const activeTab = document.getElementById(`form-${jenisLaporan}`);
    if (activeTab) {
        activeTab.classList.remove('hidden');
        activeTab.classList.add('block');
    }
});
</script>

<style>
.tab-content {
    transition: all 0.3s ease-in-out;
}
</style>
@endsection