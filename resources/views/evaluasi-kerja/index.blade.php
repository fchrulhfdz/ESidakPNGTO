@extends('layouts.app')

@section('title', 'Evaluasi Kerja')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Minimalis -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Evaluasi Kerja</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        @if(auth()->user()->isSuperAdmin())
                            Manajemen semua file evaluasi kerja
                        @else
                            Upload dan kelola file evaluasi kerja {{ auth()->user()->bagian_name }}
                        @endif
                    </p>
                </div>
                @if(auth()->user()->canUploadEvaluasiKerja())
                    <button onclick="document.getElementById('upload-form').scrollIntoView({ behavior: 'smooth' })" 
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-plus mr-2"></i>Upload Baru
                    </button>
                @endif
            </div>
        </div>

        @if(session('success') || session('error') || $errors->any())
        <div class="mb-6">
            @if(session('success'))
                <div class="p-4 bg-green-50 text-green-700 text-sm rounded-lg border border-green-200">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-red-50 text-red-700 text-sm rounded-lg border border-red-200">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-yellow-50 text-yellow-700 text-sm rounded-lg border border-yellow-200">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Terjadi kesalahan. Silakan periksa form di bawah.
                    </div>
                </div>
            @endif
        </div>
        @endif

        <!-- Upload Form -->
        @if(auth()->user()->canUploadEvaluasiKerja())
        <div id="upload-form" class="mb-8 bg-white rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-base font-medium text-gray-900">
                    <i class="fas fa-upload text-gray-400 mr-2"></i>Upload File Baru
                </h3>
            </div>
            
            <div class="p-6">
                <form action="{{ route('evaluasi-kerja.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="space-y-4">
                        <!-- Bagian (untuk super admin) -->
                        @if(auth()->user()->isSuperAdmin())
                        <div>
                            <label for="bagian" class="block text-sm font-medium text-gray-700 mb-1">
                                Bagian
                            </label>
                            <select name="bagian" id="bagian" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500">
                                <option value="">-- Pilih Bagian --</option>
                                @foreach($bagianMapping as $key => $name)
                                    @if($key !== 'super_admin')
                                        <option value="{{ $key }}" {{ old('bagian') == $key ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('bagian')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        @else
                            <input type="hidden" name="bagian" value="{{ auth()->user()->role }}">
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">Bagian:</span> {{ auth()->user()->bagian_name }}
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Judul -->
                            <div>
                                <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">
                                    Judul
                                </label>
                                <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required
                                    class="w-full px-3 py-2 text-sm border {{ $errors->has('judul') ? 'border-red-300' : 'border-gray-300' }} rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                    placeholder="Evaluasi Kinerja Triwulan I 2024">
                                @error('judul')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tahun -->
                            <div>
                                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tahun
                                </label>
                                <select name="tahun" id="tahun" required
                                    class="w-full px-3 py-2 text-sm border {{ $errors->has('tahun') ? 'border-red-300' : 'border-gray-300' }} rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500">
                                    @for($i = date('Y'); $i >= 2020; $i--)
                                        <option value="{{ $i }}" {{ old('tahun', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('tahun')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Bulan -->
                            <div>
                                <label for="bulan" class="block text-sm font-medium text-gray-700 mb-1">
                                    Periode
                                </label>
                                <select name="bulan" id="bulan" required
                                    class="w-full px-3 py-2 text-sm border {{ $errors->has('bulan') ? 'border-red-300' : 'border-gray-300' }} rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500">
                                    @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Triwulan I', 'Triwulan II', 'Triwulan III', 'Triwulan IV', 'Semester I', 'Semester II', 'Tahunan'] as $month)
                                        <option value="{{ $month }}" {{ old('bulan') == $month ? 'selected' : '' }}>{{ $month }}</option>
                                    @endforeach
                                </select>
                                @error('bulan')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- File -->
                            <div>
                                <label for="file" class="block text-sm font-medium text-gray-700 mb-1">
                                    File Word
                                </label>
                                <input type="file" name="file" id="file" accept=".doc,.docx" required
                                    class="w-full px-3 py-2 text-sm border {{ $errors->has('file') ? 'border-red-300' : 'border-gray-300' }} rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                    onchange="previewFileName(this)">
                                <p class="mt-1 text-xs text-gray-500">Format: .doc atau .docx (maks. 5MB)</p>
                                @error('file')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                                <div id="file-preview" class="mt-1 hidden">
                                    <p class="text-xs text-green-600"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">
                                Keterangan (opsional)
                            </label>
                            <textarea name="keterangan" id="keterangan" rows="2"
                                class="w-full px-3 py-2 text-sm border {{ $errors->has('keterangan') ? 'border-red-300' : 'border-gray-300' }} rounded-md focus:outline-none focus:ring-1 focus:ring-green-500 focus:border-green-500"
                                placeholder="Keterangan tambahan...">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Button -->
                        <div class="pt-2">
                            <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <i class="fas fa-upload mr-2"></i>Upload File
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Tabel Evaluasi Kerja -->
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-medium text-gray-900">
                        <i class="fas fa-file-alt text-gray-400 mr-2"></i>File Evaluasi Kerja
                    </h3>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">
                            {{ $evaluasiKerja->total() }} file
                        </span>
                        @if(auth()->user()->isSuperAdmin())
                        <div class="relative">
                            <select onchange="window.location.href = this.value ? '{{ route('evaluasi-kerja.index') }}?bagian=' + this.value : '{{ route('evaluasi-kerja.index') }}'"
                                    class="text-sm border border-gray-300 rounded-md px-3 py-1 focus:outline-none focus:ring-1 focus:ring-green-500">
                                <option value="">Semua Bagian</option>
                                @foreach($bagianMapping as $key => $name)
                                    @if($key !== 'super_admin')
                                        <option value="{{ $key }}" {{ request()->get('bagian') == $key ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($evaluasiKerja->isEmpty())
                <div class="p-12 text-center">
                    <i class="fas fa-file-word text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500 text-sm">Belum ada file evaluasi kerja</p>
                    @if(auth()->user()->canUploadEvaluasiKerja())
                        <p class="text-gray-400 text-xs mt-1">Mulai dengan mengupload file pertama Anda</p>
                    @endif
                </div>
            @else
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No
                                </th>
                                @if(auth()->user()->isSuperAdmin())
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bagian
                                </th>
                                @endif
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    File
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Periode
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($evaluasiKerja as $evaluasi)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $loop->iteration + (($evaluasiKerja->currentPage() - 1) * $evaluasiKerja->perPage()) }}
                                </td>
                                @if(auth()->user()->isSuperAdmin())
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($evaluasi->bagian == 'perdata') bg-blue-100 text-blue-800
                                        @elseif($evaluasi->bagian == 'pidana') bg-red-100 text-red-800
                                        @elseif($evaluasi->bagian == 'tipikor') bg-purple-100 text-purple-800
                                        @elseif($evaluasi->bagian == 'phi') bg-yellow-100 text-yellow-800
                                        @elseif($evaluasi->bagian == 'hukum') bg-indigo-100 text-indigo-800
                                        @elseif($evaluasi->bagian == 'ptip') bg-teal-100 text-teal-800
                                        @elseif($evaluasi->bagian == 'kepegawaian') bg-pink-100 text-pink-800
                                        @elseif($evaluasi->bagian == 'umum_keuangan') bg-orange-100 text-orange-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $evaluasi->bagian_name }}
                                    </span>
                                </td>
                                @endif
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $evaluasi->judul }}</div>
                                        <div class="text-xs text-gray-500 mt-1 flex items-center">
                                            <i class="fas fa-file-word text-gray-400 mr-1"></i>
                                            {{ Str::limit($evaluasi->nama_file, 40) }}
                                        </div>
                                        @if($evaluasi->keterangan)
                                            <div class="text-xs text-gray-600 mt-1">
                                                {{ Str::limit($evaluasi->keterangan, 50) }}
                                            </div>
                                        @endif
                                        <div class="text-xs text-gray-400 mt-1">
                                            {{ $evaluasi->created_at->translatedFormat('d M Y') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $evaluasi->bulan }} {{ $evaluasi->tahun }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('evaluasi-kerja.download', $evaluasi->id) }}" 
                                           class="text-green-600 hover:text-green-900"
                                           title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        
                                        @if(auth()->user()->canDeleteEvaluasiKerja() || (auth()->user()->role == $evaluasi->bagian))
                                        <form action="{{ route('evaluasi-kerja.destroy', $evaluasi->id) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Hapus file ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($evaluasiKerja->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $evaluasiKerja->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

<script>
    function previewFileName(input) {
        const filePreview = document.getElementById('file-preview');
        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            filePreview.querySelector('p').textContent = 'File: ' + fileName;
            filePreview.classList.remove('hidden');
        } else {
            filePreview.classList.add('hidden');
        }
    }

    // Auto-scroll jika ada error
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            const uploadForm = document.getElementById('upload-form');
            if (uploadForm) {
                uploadForm.scrollIntoView({ behavior: 'smooth' });
                // Highlight field pertama yang error
                const firstError = document.querySelector('.border-red-300');
                if (firstError) {
                    firstError.focus();
                }
            }
        });
    @endif
</script>

<style>
    /* Minimalist scrollbar */
    .overflow-hidden::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    
    .overflow-hidden::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .overflow-hidden::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    .overflow-hidden::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
    
    /* Smooth transitions */
    * {
        transition: background-color 0.2s ease, border-color 0.2s ease;
    }
</style>
@endsection