@extends('layouts.app')

@section('title', 'Umum & Keuangan - E-SIDAK')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Umum & Keuangan</h1>
            <p class="text-gray-600 mt-1">Kelola data Umum & Keuangan dan lampiran</p>
        </div>
        @if(auth()->user()->isSuperAdmin())
            <span class="bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg text-sm font-medium border border-blue-100">Super Admin</span>
        @else
            <span class="bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-lg text-sm font-medium border border-emerald-100">Admin Umum & Keuangan</span>
        @endif
    </div>

    <!-- Tab Navigation -->
    <div class="mb-8">
        <div class="flex space-x-1 bg-gray-100 p-1 rounded-xl w-fit">
            <button id="dataTab" class="tab-button active py-2 px-4 rounded-lg font-medium text-sm transition-all duration-200 bg-white text-gray-900 shadow-sm">
                Data Umum & Keuangan
            </button>
            @if(auth()->user()->isSuperAdmin())
                <button id="sasaranTab" class="tab-button py-2 px-4 rounded-lg font-medium text-sm transition-all duration-200 text-gray-600 hover:text-gray-900">
                    Sasaran Strategis
                </button>
            @endif
            <button id="inputTab" class="tab-button py-2 px-4 rounded-lg font-medium text-sm transition-all duration-200 text-gray-600 hover:text-gray-900">
                Input Data
            </button>
            <button id="lampiranTab" class="tab-button py-2 px-4 rounded-lg font-medium text-sm transition-all duration-200 text-gray-600 hover:text-gray-900">
                Lampiran
            </button>
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

    <!-- Tab Content: Data Umum & Keuangan -->
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
                        @php
                            $currentYear = date('Y');
                            $startYear = $currentYear - 5;
                        @endphp
                        @for($year = $currentYear; $year >= $startYear; $year--)
                            <option value="{{ $year }}" @if($year == $currentYear) selected @endif>{{ $year }}</option>
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
                <h2 class="text-lg font-semibold text-gray-900">Data Umum & Keuangan</h2>
                <div class="flex items-center space-x-4">
                    <p class="text-gray-600 text-sm">Total: <span id="totalData">{{ $data->count() }}</span> data</p>
                    <button id="toggleAll" class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors duration-150">Buka Semua</button>
                </div>
            </div>
            
            @if($data->count() > 0)
            <div class="divide-y divide-gray-100" id="dataContainer">
                @foreach($data as $index => $item)
                <div class="accordion-item group hover:bg-gray-50 transition-colors duration-200" data-bulan="{{ $item->bulan }}" data-tahun="{{ $item->tahun }}">
                    <button class="accordion-header w-full px-6 py-5 text-left">
                        <div class="flex justify-between items-center">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900 text-left">{{ $item->sasaran_strategis }}</h3>
                                <p class="text-sm text-gray-500 mt-1 text-left">{{ $item->indikator_kinerja }}</p>
                                <div class="flex items-center mt-2 space-x-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $item->nama_bulan }} {{ $item->tahun }}
                                    </span>
                                    <span class="text-xs text-gray-500">Target: {{ number_format($item->target, 2) }}%</span>
                                    @if($item->input_1 !== null)
                                        <span class="text-xs text-gray-500">Input 1: {{ $item->input_1 }}</span>
                                    @endif
                                </div>
                            </div>
                            <svg class="accordion-arrow h-5 w-5 text-gray-400 transform transition-transform duration-200 group-hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </button>
                    <div class="accordion-content hidden px-6 pb-5">
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Detail Data</h4>
                            <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <dt class="text-xs text-gray-500 font-medium">Data Input</dt>
                                    <dd class="text-sm text-gray-900 mt-1">{{ $item->label_input_1 }}</dd>
                                    <dd class="text-sm text-gray-900 mt-1 font-semibold">{{ $item->input_1 }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-xs text-gray-500 font-medium">Periode</dt>
                                    <dd class="text-sm text-gray-900 mt-1">{{ \Carbon\Carbon::createFromDate($item->tahun, $item->bulan, 1)->translatedFormat('F Y') }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-xs text-gray-500 font-medium">Status</dt>
                                    <dd class="text-sm text-gray-900 mt-1">
                                        @if($item->input_1 !== null)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Sudah diisi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Belum diisi
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
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
                                        data-label-input-1="{{ $item->label_input_1 }}"
                                        data-input-1="{{ $item->input_1 }}"
                                        data-bulan="{{ $item->bulan }}"
                                        data-tahun="{{ $item->tahun }}"
                                        data-jenis="umum-keuangan">
                                    Edit
                                </button>
                                <form action="{{ route('umum-keuangan.destroy', $item->id) }}" method="POST" class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors duration-150" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                            @elseif(!$item->has_input)
                            <div class="flex space-x-3">
                                <button type="button" class="text-blue-600 hover:text-blue-800 text-sm font-medium edit-btn transition-colors duration-150" 
                                        data-id="{{ $item->id }}"
                                        data-input-1="{{ $item->input_1 }}"
                                        data-jenis="umum-keuangan">
                                    Isi Data
                                </button>
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
    </div>

    <!-- Tab Content: Sasaran Strategis (Super Admin Only) -->
    @if(auth()->user()->isSuperAdmin())
    <div id="sasaranContent" class="tab-content hidden">
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Tambah Sasaran Strategis Baru</h2>
            </div>
            
            <form action="{{ route('store.umum-keuangan') }}" method="POST" id="formTambahSasaran">
                @csrf
                <input type="hidden" name="jenis" value="umum-keuangan">
                
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">Label Input 1</label>
                            <input type="text" name="label_input_1" required
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                                   value="{{ old('label_input_1') }}"
                                   placeholder="Contoh: Jumlah Kegiatan Umum & Keuangan">
                            @error('label_input_1')
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
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" @if(old('bulan') == $i) selected @endif>
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                @endfor
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

    <!-- Tab Content: Input Data (Untuk Admin Biasa dan Super Admin) -->
    <div id="inputContent" class="tab-content hidden">
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Input Data Umum & Keuangan</h2>
            
            <form action="{{ route('store.umum-keuangan') }}" method="POST">
                @csrf
                <input type="hidden" name="jenis" value="umum-keuangan">
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                            <select name="bulan" id="input_bulan" required
                                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white">
                                <option value="">Pilih Bulan</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ old('bulan') == $i ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                            @error('bulan')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <input type="number" name="tahun" id="input_tahun" required min="2000" max="2100"
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                                   value="{{ old('tahun', date('Y')) }}">
                            @error('tahun')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Form untuk Sasaran Strategis yang ada -->
                    <div id="existing_sasaran_form">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sasaran Strategis</label>
                            <select name="parent_id" id="parentSelect" required
                                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white">
                                <option value="">Pilih Sasaran Strategis</option>
                                @foreach($data->unique('sasaran_strategis') as $item)
                                    <option value="{{ $item->id }}" 
                                            data-indikator="{{ $item->indikator_kinerja }}"
                                            data-target="{{ $item->target }}"
                                            data-label="{{ $item->label_input_1 }}"
                                            {{ old('parent_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->sasaran_strategis }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Indikator Kinerja</label>
                                <input type="text" id="indikatorDisplay" readonly
                                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Target</label>
                                <input type="text" id="targetDisplay" readonly
                                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
                            </div>
                        </div>
                    </div>

                    <!-- Input untuk nilai -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span id="label_input_1_display">Label Input 1</span>
                        </label>
                        <input type="number" name="input_1" id="input_1" required min="0"
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                               value="{{ old('input_1') }}"
                               placeholder="Masukkan nilai">
                        @error('input_1')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" 
                                class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center font-medium">
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

    <!-- Tab Content: Lampiran PDF -->
    <div id="lampiranContent" class="tab-content hidden">
        <!-- Form Upload Lampiran -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6">Upload Lampiran PDF</h2>
            
            <form id="uploadLampiranForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="jenis" value="umum-keuangan">
                <div class="space-y-6">
                    <!-- Filter untuk Data Umum & Keuangan -->
                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Filter Data Umum & Keuangan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                                <select id="lampiranFilterBulan" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg">
                                    <option value="">Semua Bulan</option>
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
                                <select id="lampiranFilterTahun" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg">
                                    <option value="">Semua Tahun</option>
                                    @php
                                        $currentYear = date('Y');
                                        $startYear = $currentYear - 5;
                                    @endphp
                                    @for($year = $currentYear; $year >= $startYear; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            
                            <div class="flex items-end">
                                <button type="button" id="filterLampiranBtn" class="w-full bg-gray-600 text-white px-6 py-2.5 rounded-lg hover:bg-gray-700 transition duration-200 font-medium">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Data Umum & Keuangan</label>
                        <select name="parent_id" id="lampiranParentSelect" required
                                class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white">
                            <option value="">Pilih Data Umum & Keuangan</option>
                            @foreach($data as $item)
                                <option value="{{ $item->id }}" data-bulan="{{ $item->bulan }}" data-tahun="{{ $item->tahun }}">
                                    {{ $item->sasaran_strategis }} ({{ $item->nama_bulan }} {{ $item->tahun }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">File PDF</label>
                        <input type="file" name="lampiran" id="lampiranFile" accept=".pdf" required
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white">
                        <p class="text-xs text-gray-500 mt-1">Maksimal 5MB, format PDF</p>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" id="uploadBtn"
                                class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            Upload Lampiran
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabel Lampiran -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Daftar Lampiran PDF</h2>
                    <div class="flex items-center space-x-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            <div>
                                <select id="daftarFilterBulan" class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm w-full">
                                    <option value="">Semua Bulan</option>
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
                                <select id="daftarFilterTahun" class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm w-full">
                                    <option value="">Semua Tahun</option>
                                    @php
                                        $currentYear = date('Y');
                                        $startYear = $currentYear - 5;
                                    @endphp
                                    @for($year = $currentYear; $year >= $startYear; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <button id="daftarFilterBtn" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 text-sm w-full">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama File</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sasaran Strategis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Pengupload</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Upload</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ukuran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="lampiranTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Data akan diisi via JavaScript -->
                    </tbody>
                </table>
            </div>
            
            <div id="lampiranLoading" class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="text-gray-500 mt-2">Memuat data...</p>
            </div>
            
            <div id="lampiranEmpty" class="text-center py-8 hidden">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada lampiran</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan mengupload lampiran PDF.</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Data Umum & Keuangan -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-40 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl border border-gray-200 w-full max-w-2xl transform transition-all duration-300 scale-95">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Data Umum & Keuangan</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="jenis" id="edit_jenis">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="space-y-4 mb-4">
                    @if(auth()->user()->isSuperAdmin())
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Label Input 1</label>
                        <input type="text" name="label_input_1" id="edit_label_input_1" required
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    </div>
                    @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Input 1</label>
                        <input type="number" name="input_1" id="edit_input_1" required min="0"
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    </div>

                    @if(auth()->user()->isSuperAdmin())
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                            <select name="bulan" id="edit_bulan" required
                                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white">
                                <option value="">Pilih Bulan</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                            <input type="number" name="tahun" id="edit_tahun" required min="2020"
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   value="{{ date('Y') }}">
                        </div>
                    </div>
                    @endif
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

<!-- Modal Edit Lampiran -->
<div id="editLampiranModal" class="fixed inset-0 bg-black bg-opacity-40 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300 flex items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl border border-gray-200 w-full max-w-md transform transition-all duration-300 scale-95">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Nama Lampiran</h3>
            <form id="editLampiranForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_lampiran_id">
                
                <div class="space-y-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama File</label>
                        <input type="text" name="original_name" id="edit_lampiran_nama" required
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="closeLampiranModal" 
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
    const jenis = 'umum-keuangan';
    
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
            else if (tabId === 'lampiranTab') {
                contentId = 'lampiranContent';
                loadLampiranData(jenis);
            }
            
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
    
    // Dynamic form fields for input data
    const parentSelect = document.getElementById('parentSelect');
    if (parentSelect) {
        parentSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                document.getElementById('indikatorDisplay').value = selectedOption.getAttribute('data-indikator');
                document.getElementById('targetDisplay').value = selectedOption.getAttribute('data-target') + '%';
                document.getElementById('label_input_1_display').textContent = selectedOption.getAttribute('data-label');
            } else {
                document.getElementById('indikatorDisplay').value = '';
                document.getElementById('targetDisplay').value = '';
                document.getElementById('label_input_1_display').textContent = 'Label Input 1';
            }
        });
        
        if (parentSelect.value) {
            parentSelect.dispatchEvent(new Event('change'));
        }
    }
    
    // Edit button functionality
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const sasaran = this.getAttribute('data-sasaran');
            const indikator = this.getAttribute('data-indikator');
            const target = this.getAttribute('data-target');
            const labelInput1 = this.getAttribute('data-label-input-1');
            const input1 = this.getAttribute('data-input-1');
            const jenis = this.getAttribute('data-jenis');
            const bulan = this.getAttribute('data-bulan');
            const tahun = this.getAttribute('data-tahun');
            const isSuperAdmin = {{ auth()->user()->isSuperAdmin() ? 'true' : 'false' }};
            
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_sasaran').value = sasaran || '';
            document.getElementById('edit_indikator').value = indikator || '';
            document.getElementById('edit_target').value = target || '';
            document.getElementById('edit_label_input_1').value = labelInput1 || '';
            document.getElementById('edit_input_1').value = input1 || '';
            document.getElementById('edit_jenis').value = jenis;
            
            if (isSuperAdmin) {
                document.getElementById('edit_bulan').value = bulan || '';
                document.getElementById('edit_tahun').value = tahun || '';
            }
            
            document.getElementById('editForm').action = `/${jenis}/${id}`;
            document.getElementById('editModal').classList.remove('hidden');
        });
    });
    
    // Close modals
    const closeModalBtn = document.getElementById('closeModal');
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function() {
            document.getElementById('editModal').classList.add('hidden');
        });
    }
    
    const closeLampiranModalBtn = document.getElementById('closeLampiranModal');
    if (closeLampiranModalBtn) {
        closeLampiranModalBtn.addEventListener('click', function() {
            document.getElementById('editLampiranModal').classList.add('hidden');
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
    
    const editLampiranModal = document.getElementById('editLampiranModal');
    if (editLampiranModal) {
        editLampiranModal.addEventListener('click', function(e) {
            if (e.target === this) {
                document.getElementById('editLampiranModal').classList.add('hidden');
            }
        });
    }
    
    // ==================== FILTER DATA UMUM & KEUANGAN ====================
    const filterBulan = document.getElementById('filterBulan');
    const filterTahun = document.getElementById('filterTahun');
    const cariBtn = document.getElementById('cariBtn');
    
    function filterData() {
        const bulan = filterBulan ? filterBulan.value : '';
        const tahun = filterTahun ? filterTahun.value : '';
        const items = document.querySelectorAll('#dataContainer .accordion-item');
        let totalVisible = 0;
        
        items.forEach(item => {
            const itemBulan = item.getAttribute('data-bulan');
            const itemTahun = item.getAttribute('data-tahun');
            
            const matchBulan = !bulan || bulan === itemBulan;
            const matchTahun = !tahun || tahun === itemTahun;
            
            if (matchBulan && matchTahun) {
                item.style.display = '';
                totalVisible++;
            } else {
                item.style.display = 'none';
            }
        });
        
        // Update counter
        document.getElementById('totalData').textContent = totalVisible;
        
        // Show message if no results
        const dataContainer = document.getElementById('dataContainer');
        if (totalVisible === 0 && dataContainer) {
            if (!document.getElementById('noResultsMessage')) {
                const noResults = document.createElement('div');
                noResults.id = 'noResultsMessage';
                noResults.className = 'text-center py-12';
                noResults.innerHTML = `
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data ditemukan</h3>
                    <p class="mt-1 text-sm text-gray-500">Coba filter dengan bulan atau tahun yang berbeda.</p>
                `;
                dataContainer.parentNode.insertBefore(noResults, dataContainer.nextSibling);
            }
        } else {
            const noResults = document.getElementById('noResultsMessage');
            if (noResults) {
                noResults.remove();
            }
        }
    }
    
    // Event listeners for filters
    if (cariBtn) {
        cariBtn.addEventListener('click', filterData);
    }
    
    // Initialize filter on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Set current month as default if not already set
        if (filterBulan && !filterBulan.value) {
            const currentMonth = new Date().getMonth() + 1;
            filterBulan.value = currentMonth;
        }
        
        // Apply initial filter
        filterData();
    });
    
    // ==================== FILTER UPLOAD LAMPIRAN ====================
    const lampiranFilterBulan = document.getElementById('lampiranFilterBulan');
    const lampiranFilterTahun = document.getElementById('lampiranFilterTahun');
    const filterLampiranBtn = document.getElementById('filterLampiranBtn');
    const lampiranParentSelect = document.getElementById('lampiranParentSelect');
    
    function filterLampiranOptions() {
        const selectedBulan = lampiranFilterBulan ? lampiranFilterBulan.value : '';
        const selectedTahun = lampiranFilterTahun ? lampiranFilterTahun.value : '';
        
        let hasVisibleOptions = false;
        
        Array.from(lampiranParentSelect.options).forEach(option => {
            if (option.value === '') return;
            
            const optionBulan = option.getAttribute('data-bulan');
            const optionTahun = option.getAttribute('data-tahun');
            
            const matchBulan = !selectedBulan || selectedBulan === optionBulan;
            const matchTahun = !selectedTahun || selectedTahun === optionTahun;
            
            if (matchBulan && matchTahun) {
                option.style.display = '';
                option.disabled = false;
                hasVisibleOptions = true;
            } else {
                option.style.display = 'none';
                option.disabled = true;
            }
        });
        
        // Reset selection jika opsi yang dipilih tidak sesuai filter
        const selectedOption = lampiranParentSelect.options[lampiranParentSelect.selectedIndex];
        if (selectedOption && selectedOption.style.display === 'none') {
            lampiranParentSelect.value = '';
        }
        
        // Tampilkan pesan jika tidak ada opsi
        const filterMessage = document.getElementById('filterLampiranMessage');
        if (!hasVisibleOptions && lampiranParentSelect.options.length > 1) {
            if (!filterMessage) {
                const message = document.createElement('p');
                message.id = 'filterLampiranMessage';
                message.className = 'text-sm text-amber-600 mt-2';
                message.textContent = 'Tidak ada data yang sesuai dengan filter yang dipilih.';
                lampiranParentSelect.parentNode.appendChild(message);
            }
        } else if (filterMessage) {
            filterMessage.remove();
        }
    }
    
    if (filterLampiranBtn) {
        filterLampiranBtn.addEventListener('click', filterLampiranOptions);
    }
    
    // ==================== LAMPIRAN FUNCTIONALITY ====================
    
    let lampiranData = [];
    
    function formatFileSize(bytes) {
        if (bytes >= 1073741824) {
            return (bytes / 1073741824).toFixed(2) + ' GB';
        } else if (bytes >= 1048576) {
            return (bytes / 1048576).toFixed(2) + ' MB';
        } else if (bytes >= 1024) {
            return (bytes / 1024).toFixed(2) + ' KB';
        } else {
            return bytes + ' bytes';
        }
    }
    
    function loadLampiranData(jenis) {
        const loading = document.getElementById('lampiranLoading');
        const empty = document.getElementById('lampiranEmpty');
        const tableBody = document.getElementById('lampiranTableBody');
        const filterBulan = document.getElementById('daftarFilterBulan');
        const filterTahun = document.getElementById('daftarFilterTahun');
        
        loading.classList.remove('hidden');
        empty.classList.add('hidden');
        tableBody.innerHTML = '';
        
        const bulan = filterBulan ? filterBulan.value : '';
        const tahun = filterTahun ? filterTahun.value : '';
        
        // Build URL dengan query parameters
        let url = `/${jenis}/lampiran`;
        const params = new URLSearchParams();
        if (bulan) params.append('bulan', bulan);
        if (tahun) params.append('tahun', tahun);
        
        const queryString = params.toString();
        if (queryString) {
            url += '?' + queryString;
        }
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                loading.classList.add('hidden');
                lampiranData = data;
                
                if (data.length === 0) {
                    empty.classList.remove('hidden');
                    return;
                }
                
                tableBody.innerHTML = data.map(lampiran => {
                    const isSuperAdmin = {{ auth()->user()->isSuperAdmin() ? 'true' : 'false' }};
                    const canEdit = isSuperAdmin;
                    const canDelete = isSuperAdmin || {{ auth()->user()->id }} == lampiran.user_id;
                    const parentKey = jenis === 'ptip' ? 'ptip' : (jenis === 'umum-keuangan' ? 'umumKeuangan' : 'kepegawaian');
                    
                    return `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${lampiran.id}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <a href="/${jenis}/lampiran/${lampiran.id}/download" 
                                   class="text-blue-600 hover:text-blue-900 hover:underline"
                                   target="_blank">
                                    ${lampiran.original_name}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${lampiran[parentKey] ? lampiran[parentKey].sasaran_strategis : '-'}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${lampiran[parentKey] ? lampiran[parentKey].nama_bulan + ' ' + lampiran[parentKey].tahun : '-'}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${lampiran.user ? lampiran.user.name : '-'}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${new Date(lampiran.created_at).toLocaleDateString('id-ID', {
                                    day: '2-digit',
                                    month: 'long',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${formatFileSize(lampiran.file_size)}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                ${canEdit ? `
                                    <button class="text-blue-600 hover:text-blue-900 edit-lampiran-btn" 
                                            data-id="${lampiran.id}" 
                                            data-nama="${lampiran.original_name}">
                                        Edit
                                    </button>
                                ` : ''}
                                ${canDelete ? `
                                    <button class="text-red-600 hover:text-red-900 ml-2 delete-lampiran-btn" 
                                            data-id="${lampiran.id}">
                                        Hapus
                                    </button>
                                ` : ''}
                            </td>
                        </tr>
                    `;
                }).join('');
                
                document.querySelectorAll('.edit-lampiran-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        const nama = this.getAttribute('data-nama');
                        
                        document.getElementById('edit_lampiran_id').value = id;
                        document.getElementById('edit_lampiran_nama').value = nama;
                        document.getElementById('editLampiranModal').classList.remove('hidden');
                    });
                });
                
                document.querySelectorAll('.delete-lampiran-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        if (confirm('Apakah Anda yakin ingin menghapus lampiran ini?')) {
                            deleteLampiran(jenis, id);
                        }
                    });
                });
            })
            .catch(error => {
                console.error('Error loading lampiran:', error);
                loading.classList.add('hidden');
                empty.classList.remove('hidden');
            });
    }
    
    const daftarFilterBtn = document.getElementById('daftarFilterBtn');
    
    if (daftarFilterBtn) {
        daftarFilterBtn.addEventListener('click', function() {
            loadLampiranData(jenis);
        });
    }
    
    const uploadForm = document.getElementById('uploadLampiranForm');
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const uploadBtn = document.getElementById('uploadBtn');
            const originalText = uploadBtn.innerHTML;
            
            uploadBtn.innerHTML = '<div class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div> Mengupload...';
            uploadBtn.disabled = true;
            
            fetch(`/${jenis}/lampiran`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Lampiran berhasil diupload!');
                    uploadForm.reset();
                    loadLampiranData(jenis);
                } else {
                    alert('Error: ' + (data.error || 'Gagal mengupload lampiran'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupload lampiran');
            })
            .finally(() => {
                uploadBtn.innerHTML = originalText;
                uploadBtn.disabled = false;
            });
        });
    }
    
    const editLampiranForm = document.getElementById('editLampiranForm');
    if (editLampiranForm) {
        editLampiranForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const id = document.getElementById('edit_lampiran_id').value;
            const formData = new FormData(this);
            
            fetch(`/${jenis}/lampiran/${id}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-HTTP-Method-Override': 'PUT'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Lampiran berhasil diupdate!');
                    document.getElementById('editLampiranModal').classList.add('hidden');
                    loadLampiranData(jenis);
                } else {
                    alert('Error: ' + (data.error || 'Gagal mengupdate lampiran'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupdate lampiran');
            });
        });
    }
    
    function deleteLampiran(jenis, id) {
        if (!confirm('Apakah Anda yakin ingin menghapus lampiran ini?')) return;
        
        fetch(`/${jenis}/lampiran/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-HTTP-Method-Override': 'DELETE'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Lampiran berhasil dihapus!');
                loadLampiranData(jenis);
            } else {
                alert('Error: ' + (data.error || 'Gagal menghapus lampiran'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus lampiran');
        });
    }
    
    if (document.getElementById('lampiranContent').classList.contains('active')) {
        loadLampiranData(jenis);
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

#editModal, #editLampiranModal {
    opacity: 0;
    transition: opacity 0.3s ease;
}

#editModal:not(.hidden), #editLampiranModal:not(.hidden) {
    opacity: 1;
}

#editModal .scale-95, #editLampiranModal .scale-95 {
    transform: scale(0.95);
}

#editModal:not(.hidden) .scale-95, #editLampiranModal:not(.hidden) .scale-95 {
    transform: scale(1);
}

.filter-container {
    transition: all 0.3s ease;
}

.filter-container:hover {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

#cariBtn, #filterLampiranBtn, #daftarFilterBtn {
    transition: all 0.3s ease;
}

#cariBtn:hover, #filterLampiranBtn:hover, #daftarFilterBtn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3), 0 2px 4px -1px rgba(59, 130, 246, 0.2);
}

#cariBtn:active, #filterLampiranBtn:active, #daftarFilterBtn:active {
    transform: translateY(0);
}
</style>
@endsection