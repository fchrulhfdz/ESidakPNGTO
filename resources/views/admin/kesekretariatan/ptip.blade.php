@extends('layouts.app')

@section('title', 'PTIP - E-SIDAK')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Data Kesekretariatan PTIP</h1>
        @if(auth()->user()->isSuperAdmin())
            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">Super Admin Mode</span>
        @else
            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Admin PTIP</span>
        @endif
    </div>

    <!-- Form Tambah Data Sasaran Strategis (Hanya Super Admin) -->
    @if(auth()->user()->isSuperAdmin())
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Tambah Sasaran Strategis Baru</h2>
        <form action="{{ route('store.kesekretariatan') }}" method="POST" id="formTambahSasaran">
            @csrf
            <input type="hidden" name="jenis" value="ptip">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sasaran Strategis</label>
                    <input type="text" name="sasaran_strategis" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ old('sasaran_strategis') }}">
                    @error('sasaran_strategis')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Indikator Kinerja</label>
                    <input type="text" name="indikator_kinerja" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ old('indikator_kinerja') }}">
                    @error('indikator_kinerja')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target (%)</label>
                    <input type="number" name="target" step="0.01" required min="0" max="100"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ old('target') }}">
                    @error('target')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rumus</label>
                    <input type="text" name="rumus" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ old('rumus') }}"
                           placeholder="Contoh: (Jumlah Tepat Waktu / Jumlah Diselesaikan) × 100%">
                    @error('rumus')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition duration-200">
                    Simpan Sasaran Strategis
                </button>
            </div>
        </form>
    </div>
    @endif

    <!-- Form Input Perhitungan -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Input Data Perhitungan</h2>
        
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        <form id="formInputData" action="{{ route('store.kesekretariatan') }}" method="POST">
            @csrf
            <input type="hidden" name="jenis" value="ptip">
            <input type="hidden" name="sasaran_strategis" id="sasaran_hidden" value="{{ old('sasaran_strategis') }}">
            <input type="hidden" name="indikator_kinerja" id="indikator_hidden" value="{{ old('indikator_kinerja') }}">
            <input type="hidden" name="target" id="target_hidden" value="{{ old('target') }}">
            <input type="hidden" name="rumus" id="rumus_hidden" value="{{ old('rumus') }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sasaran Strategis</label>
                    <select id="pilihSasaran" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Sasaran Strategis</option>
                        @foreach($data as $item)
                            <option value="{{ $item->id }}" 
                                    data-sasaran="{{ $item->sasaran_strategis }}"
                                    data-indikator="{{ $item->indikator_kinerja }}"
                                    data-target="{{ $item->target }}"
                                    data-rumus="{{ $item->rumus }}"
                                    @if(old('sasaran_strategis') == $item->sasaran_strategis) selected @endif>
                                {{ Str::limit($item->sasaran_strategis, 50) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Indikator Kinerja</label>
                    <select id="pilihIndikator" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" disabled>
                        <option value="">Pilih Sasaran Strategis terlebih dahulu</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Kegiatan Diselesaikan</label>
                    <input type="number" name="input_1" id="input_1" required min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ old('input_1') }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Kegiatan Tepat Waktu</label>
                    <input type="number" name="input_2" id="input_2" required min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ old('input_2') }}">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="flex items-center space-x-4">
                    <button type="button" id="hitungBtn" 
                            class="bg-green-500 text-white px-6 py-2 rounded-md hover:bg-green-600 transition duration-200">
                        HITUNG
                    </button>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Realisasi</label>
                    <input type="text" id="realisasi" readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-center"
                           value="{{ old('realisasi') }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Capaian</label>
                    <input type="text" id="capaian" readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-center"
                           value="{{ old('capaian') }}">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" id="submitBtn" disabled
                        class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition duration-200 opacity-50 cursor-not-allowed">
                    SIMPAN DATA
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Data -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Data Kesekretariatan PTIP</h2>
            <p class="text-gray-600 text-sm mt-1">Total: {{ $data->count() }} data</p>
        </div>
        
        @if($data->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sasaran Strategis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Indikator Kinerja</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rumus</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Input</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Realisasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capaian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        @if(auth()->user()->isSuperAdmin())
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($data as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->sasaran_strategis }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->indikator_kinerja }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($item->target, 2) }}%</td>
                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                            <div class="tooltip">
                                <span class="truncate block">{{ Str::limit($item->rumus, 50) }}</span>
                                <div class="tooltiptext hidden bg-gray-800 text-white p-2 rounded text-xs max-w-md">
                                    {{ $item->rumus }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if($item->input_1 && $item->input_2)
                            <div class="space-y-1">
                                <div>Diselesaikan: <span class="font-medium">{{ $item->input_1 }}</span></div>
                                <div>Tepat Waktu: <span class="font-medium">{{ $item->input_2 }}</span></div>
                            </div>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->realisasi)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ number_format($item->realisasi, 2) }}%
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->capaian)
                            @php
                                $capaian = floatval($item->capaian);
                                $bgColor = $capaian >= 100 ? 'bg-green-100 text-green-800' : 
                                          ($capaian >= 80 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bgColor }}">
                                {{ number_format($item->capaian, 2) }}%
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $item->created_at->format('d/m/Y') }}
                        </td>
                        @if(auth()->user()->isSuperAdmin())
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button type="button" class="text-indigo-600 hover:text-indigo-900 edit-btn" 
                                        data-id="{{ $item->id }}"
                                        data-sasaran="{{ $item->sasaran_strategis }}"
                                        data-indikator="{{ $item->indikator_kinerja }}"
                                        data-target="{{ $item->target }}"
                                        data-rumus="{{ $item->rumus }}"
                                        data-jenis="ptip">
                                    Edit
                                </button>
                                <form action="{{ route('kesekretariatan.destroy', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="jenis" value="ptip">
                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">Belum ada data kesekretariatan PTIP</p>
        </div>
        @endif
    </div>
</div>

@include('admin.kesekretariatan._script')
@endsection