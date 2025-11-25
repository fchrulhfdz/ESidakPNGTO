@extends('layouts.app')

@section('title', 'Perdata - E-SIDAK')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Perkara Perdata</h1>
            <p class="text-gray-600 mt-1">Kelola data perkara perdata dan capaian kinerja</p>
        </div>
        @if(auth()->user()->isSuperAdmin())
            <span class="bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg text-sm font-medium border border-blue-100">Super Admin</span>
        @else
            <span class="bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-lg text-sm font-medium border border-emerald-100">Admin Perdata</span>
        @endif
    </div>

    <!-- Tab Navigation -->
    <div class="mb-8">
        <div class="flex space-x-1 bg-gray-100 p-1 rounded-xl w-fit">
            <button id="dataTab" class="tab-button active py-2 px-4 rounded-lg font-medium text-sm transition-all duration-200 bg-white text-gray-900 shadow-sm">
                Data Perkara
            </button>
            <button id="inputTab" class="tab-button py-2 px-4 rounded-lg font-medium text-sm transition-all duration-200 text-gray-600 hover:text-gray-900">
                Input Data
            </button>
            @if(auth()->user()->isSuperAdmin())
            <button id="sasaranTab" class="tab-button py-2 px-4 rounded-lg font-medium text-sm transition-all duration-200 text-gray-600 hover:text-gray-900">
                Sasaran Strategis
            </button>
            @endif
        </div>
    </div>

    <!-- Notifications -->
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        Terdapat {{ $errors->count() }} kesalahan dalam input
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif
    
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Tab Content: Data Perkara -->
    <div id="dataContent" class="tab-content active">
        <!-- Filter Data -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <select id="filterBulan" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white">
                        <option value="">Pilih Bulan</option>
                        <option value="1">Januari</option>
                        <option value="2">Februari</option>
                        <option value="3">Maret</option>
                        <option value="4">April</option>
                        <option value="5">Mei</option>
                        <option value="6">Juni</option>
                        <option value="7">Juli</option>
                        <option value="8">Agustus</option>
                        <option value="9">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <select id="filterTahun" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white">
                        <option value="">Pilih Tahun</option>
                        @for($year = date('Y'); $year >= 2020; $year--)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button id="cariBtn" class="w-full bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                        Cari
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Data Perkara Perdata</h2>
                <div class="flex items-center space-x-4">
                    <p class="text-gray-600 text-sm">Total: <span id="totalData">{{ $data->count() }}</span> data</p>
                    <button id="toggleAll" class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors duration-150">Buka Semua</button>
                </div>
            </div>
            
            @if($data->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($data as $index => $item)
                <div class="accordion-item group hover:bg-gray-50 transition-colors duration-200" data-bulan="{{ $item->bulan }}" data-tahun="{{ $item->tahun }}" data-realisasi="{{ $item->realisasi }}" data-capaian="{{ $item->capaian }}">
                    <button class="accordion-header w-full px-6 py-5 text-left">
                        <div class="flex justify-between items-center">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 text-left">{{ $item->sasaran_strategis }}</h3>
                                <p class="text-sm text-gray-500 mt-1 text-left">{{ $item->indikator_kinerja }}</p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="text-right">
                                    <div class="text-sm text-gray-500">Capaian</div>
                                    @if($item->capaian)
                                        @php
                                            $capaian = is_numeric($item->capaian) ? floatval($item->capaian) : 0;
                                            $bgColor = $capaian >= 100 ? 'bg-green-100 text-green-800' : 
                                                      ($capaian >= 80 ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800');
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $bgColor }}">
                                            {{ number_format($capaian, 2) }}%
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </div>
                                <svg class="accordion-arrow h-5 w-5 text-gray-400 transform transition-transform duration-200 group-hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </button>
                    <div class="accordion-content hidden px-6 pb-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Detail Sasaran</h4>
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-xs text-gray-500 font-medium">Target</dt>
                                        <dd class="text-sm text-gray-900 mt-1">
                                            @if(is_numeric($item->target))
                                                {{ number_format(floatval($item->target), 2) }}%
                                            @else
                                                {{ $item->target }}
                                            @endif
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-gray-500 font-medium">Rumus Perhitungan</dt>
                                        <dd class="text-sm text-gray-900 mt-1">{{ $item->rumus }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-gray-500 font-medium">Periode</dt>
                                        <dd class="text-sm text-gray-900 mt-1">{{ \Carbon\Carbon::createFromDate($item->tahun, $item->bulan, 1)->translatedFormat('F Y') }}</dd>
                                    </div>
                                </dl>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Data Input</h4>
                                @if($item->input_1 !== null && $item->input_2 !== null)
                                <dl class="space-y-3">
                                    <div>
                                        <dt class="text-xs text-gray-500 font-medium">
                                            {{ $item->getSafeLabelInput1() }}
                                        </dt>
                                        <dd class="text-sm font-medium text-gray-900 mt-1">{{ $item->input_1 }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-gray-500 font-medium">
                                            {{ $item->getSafeLabelInput2() }}
                                        </dt>
                                        <dd class="text-sm font-medium text-gray-900 mt-1">{{ $item->input_2 }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-gray-500 font-medium">Realisasi</dt>
                                        <dd class="text-sm mt-1">
                                            @if($item->realisasi)
                                                @php
                                                    $realisasi = is_numeric($item->realisasi) ? floatval($item->realisasi) : 0;
                                                @endphp
                                                <span class="font-medium text-gray-900">{{ number_format($realisasi, 2) }}%</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </dd>
                                    </div>
                                </dl>
                                @else
                                <p class="text-sm text-gray-500">Belum ada data input</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mt-5 pt-4 border-t border-gray-200 flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                Terakhir diupdate: {{ $item->updated_at ? $item->updated_at->format('d/m/Y H:i') : '-' }}
                            </div>
                            @if(auth()->user()->isSuperAdmin())
                            <div class="flex space-x-3">
                                <button type="button" class="text-blue-600 hover:text-blue-800 text-sm font-medium edit-btn transition-colors duration-150" 
                                        data-id="{{ $item->id }}"
                                        data-sasaran="{{ $item->sasaran_strategis }}"
                                        data-indikator="{{ $item->indikator_kinerja }}"
                                        data-target="{{ $item->target }}"
                                        data-rumus="{{ $item->rumus }}"
                                        data-bulan="{{ $item->bulan }}"
                                        data-tahun="{{ $item->tahun }}"
                                        data-label-input-1="{{ $item->label_input_1 }}"
                                        data-label-input-2="{{ $item->label_input_2 }}"
                                        data-jenis="perdata">
                                    Edit
                                </button>
                                <form action="{{ route('perkara.destroy', $item->id) }}" method="POST" class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="jenis" value="perdata">
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors duration-150" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada data</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan sasaran strategis baru.</p>
            </div>
            @endif
        </div>

        <!-- Tabel Ringkasan Realisasi dan Capaian -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mt-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Ringkasan Total Realisasi dan Capaian</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Realisasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Capaian</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="ringkasanTableBody">
                        @php
                            $totalRealisasi = 0;
                            $totalCapaian = 0;
                            $count = 0;
                        @endphp
                        @foreach($data as $item)
                            @php
                                $realisasi = is_numeric($item->realisasi) ? floatval($item->realisasi) : 0;
                                $capaian = is_numeric($item->capaian) ? floatval($item->capaian) : 0;
                                $totalRealisasi += $realisasi;
                                $totalCapaian += $capaian;
                                $count++;
                            @endphp
                        @endforeach
                        <tr class="bg-gray-50 font-semibold">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ number_format($totalRealisasi, 2) }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $avgCapaian = $count > 0 ? $totalCapaian / $count : 0;
                                    $bgColor = $avgCapaian >= 100 ? 'bg-green-100 text-green-800' : 
                                              ($avgCapaian >= 80 ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800');
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bgColor }}">
                                    {{ number_format($avgCapaian, 2) }}%
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tab Content: Input Data -->
    <div id="inputContent" class="tab-content hidden">
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Input Data Perhitungan</h2>
            
            <form id="formInputData" action="{{ route('store.perkara') }}" method="POST">
                @csrf
                <input type="hidden" name="jenis" value="perdata">
                <input type="hidden" name="sasaran_strategis" id="sasaran_hidden" value="{{ old('sasaran_strategis') }}">
                <input type="hidden" name="indikator_kinerja" id="indikator_hidden" value="{{ old('indikator_kinerja') }}">
                <input type="hidden" name="target" id="target_hidden" value="{{ old('target') }}">
                <input type="hidden" name="rumus" id="rumus_hidden" value="{{ old('rumus') }}">
                <input type="hidden" name="bulan" id="bulan_hidden" value="{{ old('bulan') }}">
                <input type="hidden" name="tahun" id="tahun_hidden" value="{{ old('tahun') }}">
                <!-- TAMBAHAN: Hidden field untuk label input -->
                <input type="hidden" name="label_input_1" id="label_input_1_hidden" value="{{ old('label_input_1') }}">
                <input type="hidden" name="label_input_2" id="label_input_2_hidden" value="{{ old('label_input_2') }}">

                <div class="space-y-6">
                    <div>
                        <!-- DROPDOWN BULAN -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                            <select name="bulan" id="bulan" required
                                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white">
                                <option value="">Pilih Bulan</option>
                                <option value="1" {{ old('bulan') == '1' ? 'selected' : '' }}>Januari</option>
                                <option value="2" {{ old('bulan') == '2' ? 'selected' : '' }}>Februari</option>
                                <option value="3" {{ old('bulan') == '3' ? 'selected' : '' }}>Maret</option>
                                <option value="4" {{ old('bulan') == '4' ? 'selected' : '' }}>April</option>
                                <option value="5" {{ old('bulan') == '5' ? 'selected' : '' }}>Mei</option>
                                <option value="6" {{ old('bulan') == '6' ? 'selected' : '' }}>Juni</option>
                                <option value="7" {{ old('bulan') == '7' ? 'selected' : '' }}>Juli</option>
                                <option value="8" {{ old('bulan') == '8' ? 'selected' : '' }}>Agustus</option>
                                <option value="9" {{ old('bulan') == '9' ? 'selected' : '' }}>September</option>
                                <option value="10" {{ old('bulan') == '10' ? 'selected' : '' }}>Oktober</option>
                                <option value="11" {{ old('bulan') == '11' ? 'selected' : '' }}>November</option>
                                <option value="12" {{ old('bulan') == '12' ? 'selected' : '' }}>Desember</option>
                            </select>
                        </div>
                        
                        <!-- INPUT TAHUN -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <input type="number" name="tahun" id="tahun" required min="2000" max="2100"
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                                   value="{{ old('tahun', date('Y')) }}">
                        </div>

                        <!-- DROPDOWN SASARAN STRATEGIS -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sasaran Strategis</label>
                            <select id="pilihSasaran" required
                                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white">
                                <option value="">Pilih Sasaran Strategis</option>
                                @foreach($data as $item)
                                    <option value="{{ $item->id }}" 
                                            data-sasaran="{{ $item->sasaran_strategis }}"
                                            data-indikator="{{ $item->indikator_kinerja }}"
                                            data-target="{{ $item->target }}"
                                            data-rumus="{{ $item->rumus }}"
                                            data-bulan="{{ $item->bulan }}"
                                            data-tahun="{{ $item->tahun }}"
                                            data-label-input-1="{{ $item->label_input_1 }}"
                                            data-label-input-2="{{ $item->label_input_2 }}"
                                            @if(old('sasaran_strategis') == $item->sasaran_strategis) selected @endif>
                                        {{ Str::limit($item->sasaran_strategis, 50) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- DROPDOWN INDIKATOR KINERJA -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Indikator Kinerja</label>
                            <select id="pilihIndikator" required
                                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white">
                                <option value="">Pilih Indikator Kinerja</option>
                                @foreach($data as $item)
                                    <option value="{{ $item->id }}" 
                                            data-sasaran="{{ $item->sasaran_strategis }}"
                                            data-indikator="{{ $item->indikator_kinerja }}"
                                            data-target="{{ $item->target }}"
                                            data-rumus="{{ $item->rumus }}"
                                            data-bulan="{{ $item->bulan }}"
                                            data-tahun="{{ $item->tahun }}"
                                            data-label-input-1="{{ $item->label_input_1 }}"
                                            data-label-input-2="{{ $item->label_input_2 }}"
                                            @if(old('indikator_kinerja') == $item->indikator_kinerja) selected @endif>
                                        {{ Str::limit($item->indikator_kinerja, 50) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" id="label_input_1">
                                Label input 1
                            </label>
                            <input type="number" name="input_1" id="input_1" required min="0"
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                                   value="{{ old('input_1') }}">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" id="label_input_2">
                                Label input 2
                            </label>
                            <input type="number" name="input_2" id="input_2" required min="0"
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                                   value="{{ old('input_2') }}">
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <h3 class="text-sm font-medium text-blue-800 mb-3">Hasil Perhitungan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-blue-700 mb-1 font-medium">Realisasi</label>
                                <input type="text" id="realisasi" readonly
                                       class="w-full px-3 py-2.5 border border-blue-300 rounded-lg bg-white text-center font-semibold text-blue-800"
                                       value="{{ old('realisasi') }}">
                            </div>
                            
                            <div>
                                <label class="block text-xs text-blue-700 mb-1 font-medium">Capaian</label>
                                <input type="text" id="capaian" readonly
                                       class="w-full px-3 py-2.5 border border-blue-300 rounded-lg bg-white text-center font-semibold text-blue-800"
                                       value="{{ old('capaian') }}">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-2">
                        <button type="button" id="hitungBtn" 
                                class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Hitung
                        </button>
                        
                        <button type="submit" id="submitBtn" disabled
                                class="bg-gray-300 text-gray-500 px-6 py-2.5 rounded-lg transition duration-200 flex items-center font-medium cursor-not-allowed">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tab Content: Sasaran Strategis (Super Admin Only) -->
@if(auth()->user()->isSuperAdmin())
<div id="sasaranContent" class="tab-content hidden">
    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Tambah Sasaran Strategis Baru</h2>
            <button id="infoSasaran" class="text-gray-400 hover:text-gray-600 transition-colors duration-150">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </button>
        </div>
        
        <form action="{{ route('store.perkara') }}" method="POST" id="formTambahSasaran">
            @csrf
            <input type="hidden" name="jenis" value="perdata">
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sasaran Strategis</label>
                    <input type="text" name="sasaran_strategis" required
                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                           value="{{ old('sasaran_strategis') }}"
                           placeholder="Masukkan sasaran strategis">
                    @error('sasaran_strategis')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Indikator Kinerja</label>
                    <input type="text" name="indikator_kinerja" required
                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                           value="{{ old('indikator_kinerja') }}"
                           placeholder="Masukkan indikator kinerja">
                    @error('indikator_kinerja')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target (%)</label>
                        <input type="number" name="target" step="0.01" required min="0" max="100"
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                               value="{{ old('target') }}"
                               placeholder="0.00">
                        @error('target')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rumus</label>
                        <input type="text" name="rumus" required
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                               value="{{ old('rumus') }}"
                               placeholder="Contoh: (Jumlah Tepat Waktu / Jumlah Diselesaikan) × 100%">
                        @error('rumus')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- TAMBAHAN: Input Label untuk Input 1 dan Input 2 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Label Input 1</label>
                        <input type="text" name="label_input_1" required
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                               value="{{ old('label_input_1') }}"
                               placeholder="Contoh: Jumlah Perkara Diselesaikan">
                        @error('label_input_1')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Label Input 2</label>
                        <input type="text" name="label_input_2" required
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                               value="{{ old('label_input_2') }}"
                               placeholder="Contoh: Jumlah Perkara Tepat Waktu">
                        @error('label_input_2')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                        <select name="bulan" required
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white">
                            <option value="">Pilih Bulan</option>
                            <option value="1" @if(old('bulan') == '1') selected @endif>Januari</option>
                            <option value="2" @if(old('bulan') == '2') selected @endif>Februari</option>
                            <option value="3" @if(old('bulan') == '3') selected @endif>Maret</option>
                            <option value="4" @if(old('bulan') == '4') selected @endif>April</option>
                            <option value="5" @if(old('bulan') == '5') selected @endif>Mei</option>
                            <option value="6" @if(old('bulan') == '6') selected @endif>Juni</option>
                            <option value="7" @if(old('bulan') == '7') selected @endif>Juli</option>
                            <option value="8" @if(old('bulan') == '8') selected @endif>Agustus</option>
                            <option value="9" @if(old('bulan') == '9') selected @endif>September</option>
                            <option value="10" @if(old('bulan') == '10') selected @endif>Oktober</option>
                            <option value="11" @if(old('bulan') == '11') selected @endif>November</option>
                            <option value="12" @if(old('bulan') == '12') selected @endif>Desember</option>
                        </select>
                        @error('bulan')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                        <input type="number" name="tahun" required min="2020"
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                               value="{{ old('tahun', date('Y')) }}"
                               placeholder="2025">
                        @error('tahun')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Simpan Sasaran Strategis
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

<!-- Modal Edit -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-40 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl border border-gray-200 w-full max-w-2xl transform transition-all duration-300 scale-95">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Data</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="jenis" id="edit_jenis">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="space-y-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sasaran Strategis</label>
                        <input type="text" name="sasaran_strategis" id="edit_sasaran" required
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Indikator Kinerja</label>
                        <input type="text" name="indikator_kinerja" id="edit_indikator" required
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target (%)</label>
                        <input type="number" name="target" id="edit_target" step="0.01" required min="0" max="100"
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rumus</label>
                        <input type="text" name="rumus" id="edit_rumus" required
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    </div>

                    <!-- TAMBAHAN: Input Label untuk Input 1 dan Input 2 di Modal Edit -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Label Input 1</label>
                            <input type="text" name="label_input_1" id="edit_label_input_1" required
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Label Input 2</label>
                            <input type="text" name="label_input_2" id="edit_label_input_2" required
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                            <select name="bulan" id="edit_bulan" required
                                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white">
                                <option value="">Pilih Bulan</option>
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <input type="number" name="tahun" id="edit_tahun" required min="2020"
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   value="{{ date('Y') }}">
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="closeModal" 
                            class="px-4 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200 font-medium">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-medium">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabs = document.querySelectorAll('.tab-button');
    const contents = document.querySelectorAll('.tab-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => {
                t.classList.remove('active', 'bg-white', 'text-gray-900', 'shadow-sm');
                t.classList.add('text-gray-600');
            });
            contents.forEach(c => {
                c.classList.remove('active');
                c.classList.add('hidden');
            });
            
            this.classList.remove('text-gray-600');
            this.classList.add('active', 'bg-white', 'text-gray-900', 'shadow-sm');
            
            const tabId = this.id;
            let contentId;
            
            if (tabId === 'dataTab') contentId = 'dataContent';
            else if (tabId === 'inputTab') contentId = 'inputContent';
            else if (tabId === 'sasaranTab') contentId = 'sasaranContent';
            
            if (contentId) {
                document.getElementById(contentId).classList.remove('hidden');
                document.getElementById(contentId).classList.add('active');
            }
        });
    });
    
    // Accordion functionality
    const accordionHeaders = document.querySelectorAll('.accordion-header');
    const toggleAllBtn = document.getElementById('toggleAll');
    let allOpen = false;
    
    accordionHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const arrow = this.querySelector('.accordion-arrow');
            
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                arrow.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }
        });
    });
    
    if (toggleAllBtn) {
        toggleAllBtn.addEventListener('click', function() {
            const contents = document.querySelectorAll('.accordion-content');
            const arrows = document.querySelectorAll('.accordion-arrow');
            
            if (allOpen) {
                contents.forEach(content => content.classList.add('hidden'));
                arrows.forEach(arrow => arrow.classList.remove('rotate-180'));
                this.textContent = 'Buka Semua';
            } else {
                contents.forEach(content => content.classList.remove('hidden'));
                arrows.forEach(arrow => arrow.classList.add('rotate-180'));
                this.textContent = 'Tutup Semua';
            }
            
            allOpen = !allOpen;
        });
    }
    
    // Filter Bulan dan Tahun
    const filterBulan = document.getElementById('filterBulan');
    const filterTahun = document.getElementById('filterTahun');
    const cariBtn = document.getElementById('cariBtn');
    const totalDataSpan = document.getElementById('totalData');

    // Set current month and year as default
    const now = new Date();
    const currentMonth = String(now.getMonth() + 1);
    const currentYear = String(now.getFullYear());

    // Set nilai default filter ke bulan dan tahun saat ini
    if (filterBulan) filterBulan.value = currentMonth;
    if (filterTahun) filterTahun.value = currentYear;

    // Filter data saat halaman dimuat
    filterData();

    if (cariBtn) {
        cariBtn.addEventListener('click', function() {
            filterData();
        });
    }

    function filterData() {
        const bulan = String(filterBulan?.value || '');
        const tahun = String(filterTahun?.value || '');
        
        if (!bulan || !tahun) {
            alert('Silahkan pilih bulan dan tahun terlebih dahulu');
            return;
        }
        
        const accordionItems = document.querySelectorAll('.accordion-item');
        let visibleCount = 0;
        
        accordionItems.forEach(item => {
            const itemBulan = String(item.getAttribute('data-bulan'));
            const itemTahun = String(item.getAttribute('data-tahun'));
            
            if (itemBulan === bulan && itemTahun === tahun) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        if (totalDataSpan) {
            totalDataSpan.textContent = visibleCount;
        }
        updateRingkasanTable(bulan, tahun);
    }

    function updateRingkasanTable(bulan, tahun) {
        const accordionItems = document.querySelectorAll('.accordion-item');
        const ringkasanBody = document.getElementById('ringkasanTableBody');
        
        if (!ringkasanBody) return;
        
        let totalRealisasi = 0;
        let totalCapaian = 0;
        let count = 0;
        
        accordionItems.forEach(item => {
            if (item.style.display !== 'none') {
                const realisasi = parseFloat(item.getAttribute('data-realisasi') || 0);
                const capaian = parseFloat(item.getAttribute('data-capaian') || 0);
                
                totalRealisasi += realisasi;
                totalCapaian += capaian;
                count++;
            }
        });
        
        const avgRealisasi = count > 0 ? totalRealisasi / count : 0;
        const avgCapaian = count > 0 ? totalCapaian / count : 0;
        const avgCapaianColor = avgCapaian >= 100 ? 'bg-green-100 text-green-800' : 
                               (avgCapaian >= 80 ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800');
        
        const tableHtml = `
            <tr class="bg-gray-50 font-semibold">
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        ${avgRealisasi.toFixed(2)}%
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${avgCapaianColor}">
                        ${avgCapaian.toFixed(2)}%
                    </span>
                </td>
            </tr>
        `;
        
        ringkasanBody.innerHTML = tableHtml;
    }
    
    // Edit button functionality - DIPERBARUI DENGAN LABEL
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const sasaran = this.getAttribute('data-sasaran');
            const indikator = this.getAttribute('data-indikator');
            const target = this.getAttribute('data-target');
            const rumus = this.getAttribute('data-rumus');
            const jenis = this.getAttribute('data-jenis');
            const bulan = this.getAttribute('data-bulan');
            const tahun = this.getAttribute('data-tahun');
            const labelInput1 = this.getAttribute('data-label-input-1');
            const labelInput2 = this.getAttribute('data-label-input-2');
            
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_sasaran').value = sasaran;
            document.getElementById('edit_indikator').value = indikator;
            document.getElementById('edit_target').value = target;
            document.getElementById('edit_rumus').value = rumus;
            document.getElementById('edit_jenis').value = jenis;
            document.getElementById('edit_bulan').value = bulan;
            document.getElementById('edit_tahun').value = tahun;
            document.getElementById('edit_label_input_1').value = labelInput1 || '';
            document.getElementById('edit_label_input_2').value = labelInput2 || '';
            
            document.getElementById('editForm').action = `{{ url('perkara') }}/${id}`;
            document.getElementById('editModal').classList.remove('hidden');
        });
    });
    
    // Close modal
    const closeModalBtn = document.getElementById('closeModal');
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function() {
            document.getElementById('editModal').classList.add('hidden');
        });
    }
    
    // Close modal when clicking outside
    const editModal = document.getElementById('editModal');
    if (editModal) {
        editModal.addEventListener('click', function(e) {
            if (e.target === this) {
                document.getElementById('editModal').classList.add('hidden');
            }
        });
    }
    
    // Info tooltip for sasaran strategis
    const infoBtn = document.getElementById('infoSasaran');
    if (infoBtn) {
        infoBtn.addEventListener('click', function() {
            alert('Sasaran strategis adalah tujuan jangka panjang yang ingin dicapai. Indikator kinerja adalah ukuran untuk menilai pencapaian sasaran tersebut.');
        });
    }
    
    // Form input data functionality - DIPERBARUI DENGAN LABEL DINAMIS
    const pilihSasaran = document.getElementById('pilihSasaran');
    const pilihIndikator = document.getElementById('pilihIndikator');
    const sasaranHidden = document.getElementById('sasaran_hidden');
    const indikatorHidden = document.getElementById('indikator_hidden');
    const targetHidden = document.getElementById('target_hidden');
    const rumusHidden = document.getElementById('rumus_hidden');
    const bulanHidden = document.getElementById('bulan_hidden');
    const tahunHidden = document.getElementById('tahun_hidden');
    
    // TAMBAHAN: Hidden field untuk label input
    const labelInput1Hidden = document.getElementById('label_input_1_hidden');
    const labelInput2Hidden = document.getElementById('label_input_2_hidden');
    
    // Elemen dropdown bulan dan input tahun yang terlihat
    const bulanDropdown = document.getElementById('bulan');
    const tahunInput = document.getElementById('tahun');
    
    // Elemen label untuk input 1 dan input 2
    const labelInput1 = document.getElementById('label_input_1');
    const labelInput2 = document.getElementById('label_input_2');
    
    // Fungsi untuk filter sasaran strategis berdasarkan bulan dan tahun
    function filterSasaranStrategis() {
        const selectedBulan = bulanDropdown?.value || '';
        const selectedTahun = tahunInput?.value || '';
        
        if (!pilihSasaran) return;
        
        // Loop melalui semua opsi sasaran strategis
        const options = pilihSasaran.querySelectorAll('option');
        let hasVisibleOptions = false;
        
        options.forEach(option => {
            if (option.value === '') {
                // Opsi placeholder, selalu tampilkan
                option.style.display = '';
                return;
            }
            
            const optionBulan = option.getAttribute('data-bulan');
            const optionTahun = option.getAttribute('data-tahun');
            
            // Tampilkan hanya jika bulan dan tahun sesuai
            if (optionBulan === selectedBulan && optionTahun === selectedTahun) {
                option.style.display = '';
                hasVisibleOptions = true;
            } else {
                option.style.display = 'none';
            }
        });
        
        // Reset pilihan jika opsi yang dipilih tidak sesuai dengan filter
        const selectedOption = pilihSasaran.options[pilihSasaran.selectedIndex];
        if (selectedOption && selectedOption.style.display === 'none') {
            pilihSasaran.value = '';
            resetFormFields();
        }
        
        // Tampilkan pesan jika tidak ada opsi yang tersedia
        showNoDataMessage(pilihSasaran, hasVisibleOptions, selectedBulan, selectedTahun, 'sasaran strategis');
    }
    
    // Fungsi untuk filter indikator kinerja berdasarkan bulan dan tahun
    function filterIndikatorKinerja() {
        const selectedBulan = bulanDropdown?.value || '';
        const selectedTahun = tahunInput?.value || '';
        
        if (!pilihIndikator) return;
        
        // Loop melalui semua opsi indikator kinerja
        const options = pilihIndikator.querySelectorAll('option');
        let hasVisibleOptions = false;
        
        options.forEach(option => {
            if (option.value === '') {
                // Opsi placeholder, selalu tampilkan
                option.style.display = '';
                return;
            }
            
            const optionBulan = option.getAttribute('data-bulan');
            const optionTahun = option.getAttribute('data-tahun');
            
            // Tampilkan hanya jika bulan dan tahun sesuai
            if (optionBulan === selectedBulan && optionTahun === selectedTahun) {
                option.style.display = '';
                hasVisibleOptions = true;
            } else {
                option.style.display = 'none';
            }
        });
        
        // Reset pilihan jika opsi yang dipilih tidak sesuai dengan filter
        const selectedOption = pilihIndikator.options[pilihIndikator.selectedIndex];
        if (selectedOption && selectedOption.style.display === 'none') {
            pilihIndikator.value = '';
            resetFormFields();
        }
        
        // Tampilkan pesan jika tidak ada opsi yang tersedia
        showNoDataMessage(pilihIndikator, hasVisibleOptions, selectedBulan, selectedTahun, 'indikator kinerja');
    }
    
    // Fungsi untuk menampilkan pesan tidak ada data
    function showNoDataMessage(selectElement, hasVisibleOptions, bulan, tahun, type) {
        if (!hasVisibleOptions && bulan && tahun) {
            // Tambahkan pesan sementara di dropdown
            const existingMessage = selectElement.querySelector('.no-data-message');
            if (!existingMessage) {
                const messageOption = document.createElement('option');
                messageOption.value = '';
                messageOption.textContent = `Tidak ada ${type} untuk bulan dan tahun yang dipilih`;
                messageOption.disabled = true;
                messageOption.selected = true;
                messageOption.classList.add('no-data-message');
                selectElement.appendChild(messageOption);
            }
        } else {
            // Hapus pesan jika ada
            const existingMessage = selectElement.querySelector('.no-data-message');
            if (existingMessage) {
                existingMessage.remove();
            }
        }
    }
    
    // Fungsi untuk reset semua field form
    function resetFormFields() {
        sasaranHidden.value = '';
        indikatorHidden.value = '';
        targetHidden.value = '';
        rumusHidden.value = '';
        bulanHidden.value = '';
        tahunHidden.value = '';
        
        // TAMBAHAN: Reset hidden field untuk label
        if (labelInput1Hidden) labelInput1Hidden.value = '';
        if (labelInput2Hidden) labelInput2Hidden.value = '';
        
        // Reset label ke default
        if (labelInput1) labelInput1.textContent = 'Label input 1';
        if (labelInput2) labelInput2.textContent = 'Label input 2';
        
        // Reset tombol submit
        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.remove('bg-green-600', 'text-white', 'hover:bg-green-700', 'cursor-pointer');
            submitBtn.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        }
        
        // Reset hasil perhitungan
        const realisasiInput = document.getElementById('realisasi');
        const capaianInput = document.getElementById('capaian');
        if (realisasiInput) realisasiInput.value = '';
        if (capaianInput) capaianInput.value = '';
    }
    
    // Fungsi untuk mengisi form fields dari dropdown yang dipilih - DIPERBARUI DENGAN LABEL
    function fillFormFields(selectedOption) {
        if (selectedOption.value) {
            sasaranHidden.value = selectedOption.getAttribute('data-sasaran');
            indikatorHidden.value = selectedOption.getAttribute('data-indikator');
            targetHidden.value = selectedOption.getAttribute('data-target');
            rumusHidden.value = selectedOption.getAttribute('data-rumus');
            
            // Ambil label input dari data attribute
            const labelInput1Value = selectedOption.getAttribute('data-label-input-1');
            const labelInput2Value = selectedOption.getAttribute('data-label-input-2');
            
            // Update label di form input data
            if (labelInput1 && labelInput1Value) {
                labelInput1.textContent = labelInput1Value;
            }
            if (labelInput2 && labelInput2Value) {
                labelInput2.textContent = labelInput2Value;
            }
            
            // TAMBAHAN: Update hidden fields untuk label
            if (labelInput1Hidden) labelInput1Hidden.value = labelInput1Value;
            if (labelInput2Hidden) labelInput2Hidden.value = labelInput2Value;
            
            // Isi data bulan dan tahun dari data attribute
            const dataBulan = selectedOption.getAttribute('data-bulan');
            const dataTahun = selectedOption.getAttribute('data-tahun');
            
            bulanHidden.value = dataBulan;
            tahunHidden.value = dataTahun;
            
            // Update dropdown bulan dan input tahun yang terlihat
            if (bulanDropdown && dataBulan) {
                bulanDropdown.value = dataBulan;
            }
            if (tahunInput && dataTahun) {
                tahunInput.value = dataTahun;
            }
            
            // Sync antara dropdown sasaran dan indikator
            syncDropdowns(selectedOption);
        } else {
            resetFormFields();
        }
    }
    
    // Fungsi untuk sync antara dropdown sasaran dan indikator
    function syncDropdowns(selectedOption) {
        const selectedId = selectedOption.value;
        const selectedType = selectedOption.parentElement.id;
        
        if (selectedType === 'pilihSasaran' && pilihIndikator) {
            // Jika yang dipilih adalah sasaran, set indikator yang sesuai
            pilihIndikator.value = selectedId;
        } else if (selectedType === 'pilihIndikator' && pilihSasaran) {
            // Jika yang dipilih adalah indikator, set sasaran yang sesuai
            pilihSasaran.value = selectedId;
        }
    }
    
    // Panggil fungsi filter saat halaman dimuat
    filterSasaranStrategis();
    filterIndikatorKinerja();
    
    // Event listener untuk dropdown sasaran strategis
    if (pilihSasaran) {
        pilihSasaran.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            // Skip jika ini adalah pesan "no data"
            if (selectedOption.classList.contains('no-data-message')) {
                return;
            }
            
            fillFormFields(selectedOption);
        });
    }
    
    // Event listener untuk dropdown indikator kinerja
    if (pilihIndikator) {
        pilihIndikator.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            // Skip jika ini adalah pesan "no data"
            if (selectedOption.classList.contains('no-data-message')) {
                return;
            }
            
            fillFormFields(selectedOption);
        });
    }
    
    // Event listener untuk update hidden field ketika user mengubah bulan/tahun secara manual
    if (bulanDropdown) {
        bulanDropdown.addEventListener('change', function() {
            bulanHidden.value = this.value;
            // Filter ulang sasaran strategis dan indikator kinerja saat bulan berubah
            filterSasaranStrategis();
            filterIndikatorKinerja();
        });
    }
    
    if (tahunInput) {
        tahunInput.addEventListener('input', function() {
            tahunHidden.value = this.value;
            // Filter ulang sasaran strategis dan indikator kinerja saat tahun berubah
            filterSasaranStrategis();
            filterIndikatorKinerja();
        });
    }
    
    // Hitung functionality dengan validasi bulan dan tahun
    const hitungBtn = document.getElementById('hitungBtn');
    const submitBtn = document.getElementById('submitBtn');
    const input1 = document.getElementById('input_1');
    const input2 = document.getElementById('input_2');
    const realisasiInput = document.getElementById('realisasi');
    const capaianInput = document.getElementById('capaian');
    
    if (hitungBtn) {
        hitungBtn.addEventListener('click', function() {
            const perkaraDiselesaikan = parseFloat(input1.value) || 0;
            const perkaraTepatWaktu = parseFloat(input2.value) || 0;
            const target = parseFloat(targetHidden.value) || 0;
            
            // Validasi input wajib
            if (perkaraDiselesaikan === 0) {
                alert('Jumlah perkara diselesaikan tidak boleh 0');
                return;
            }
            
            // Validasi bulan dan tahun
            if (!bulanHidden.value || !tahunHidden.value) {
                alert('Silakan pilih bulan dan tahun terlebih dahulu');
                return;
            }
            
            // Validasi sasaran strategis
            if (!sasaranHidden.value) {
                alert('Silakan pilih sasaran strategis terlebih dahulu');
                return;
            }
            
            // Validasi indikator kinerja
            if (!indikatorHidden.value) {
                alert('Silakan pilih indikator kinerja terlebih dahulu');
                return;
            }
            
            // Hitung realisasi
            const realisasi = (perkaraTepatWaktu / perkaraDiselesaikan) * 100;
            
            // Hitung capaian (Realisasi / Target * 100)
            const capaian = target > 0 ? (realisasi / target) * 100 : 0;
            
            realisasiInput.value = realisasi.toFixed(2) + '%';
            capaianInput.value = capaian.toFixed(2) + '%';
            
            // Enable submit button
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
            submitBtn.classList.add('bg-green-600', 'text-white', 'hover:bg-green-700', 'cursor-pointer');
        });
    }

    // Form validation sebelum submit
    const formInputData = document.getElementById('formInputData');
    if (formInputData) {
        formInputData.addEventListener('submit', function(e) {
            // Validasi final sebelum submit
            if (!bulanHidden.value || !tahunHidden.value) {
                e.preventDefault();
                alert('Bulan dan tahun harus diisi');
                return;
            }
            
            if (!sasaranHidden.value) {
                e.preventDefault();
                alert('Sasaran strategis harus dipilih');
                return;
            }
            
            if (!indikatorHidden.value) {
                e.preventDefault();
                alert('Indikator kinerja harus dipilih');
                return;
            }
            
            if (!input1.value || !input2.value) {
                e.preventDefault();
                alert('Data input perkara harus diisi');
                return;
            }
            
            // Pastikan realisasi dan capaian sudah dihitung
            if (!realisasiInput.value || !capaianInput.value) {
                e.preventDefault();
                alert('Silakan klik tombol Hitung terlebih dahulu');
                return;
            }
        });
    }
});
</script>

<style>
.tab-content {
    transition: opacity 0.3s ease;
}

.accordion-content {
    transition: max-height 0.3s ease;
    overflow: hidden;
}

.accordion-arrow {
    transition: transform 0.3s ease;
}

.rotate-180 {
    transform: rotate(180deg);
}

#editModal {
    opacity: 0;
    transition: opacity 0.3s ease;
}

#editModal:not(.hidden) {
    opacity: 1;
}

#editModal .scale-95 {
    transform: scale(0.95);
}

#editModal:not(.hidden) .scale-95 {
    transform: scale(1);
}
</style>
@endsection