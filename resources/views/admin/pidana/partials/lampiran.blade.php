<!-- Form Upload Lampiran -->
@if(auth()->check() && auth()->user()->role !== 'read_only')
<div class="bg-white rounded-2xl border border-gray-200 p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-6">Upload Lampiran PDF</h2>
    
    <form id="uploadLampiranForm" enctype="multipart/form-data">
        @csrf
        <div class="space-y-6">
            <!-- Filter untuk Data Pidana -->
            <div class="bg-gray-50 rounded-xl p-4 mb-4">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Filter Data Pidana</h4>
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
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Indikator Kinerja</label>
                        <input type="text" id="lampiranFilterIndikator" 
                               class="w-full px-3 py-2.5 border border-gray-300 rounded-lg"
                               placeholder="Cari indikator kinerja...">
                    </div>
                    
                    <div class="flex items-end">
                        <button type="button" id="filterLampiranBtn" class="w-full bg-gray-600 text-white px-6 py-2.5 rounded-lg hover:bg-gray-700 transition duration-200 font-medium">
                            Filter
                        </button>
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Data Pidana</label>
                <select name="parent_id" id="lampiranPidanaSelect" required
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white">
                    <option value="">Pilih Data Pidana</option>
                    @foreach($data as $item)
                        <option value="{{ $item->id }}" 
                                data-bulan="{{ $item->bulan }}" 
                                data-tahun="{{ $item->tahun }}"
                                data-indikator="{{ $item->indikator_kinerja }}"
                                data-sasaran="{{ $item->sasaran_strategis }}"
                                data-capaian="{{ $item->capaian }}">
                            {{ $item->indikator_kinerja }} ({{ $item->nama_bulan }} {{ $item->tahun }})
                            @if($item->capaian)
                                - Capaian: {{ number_format($item->capaian, 2) }}%
                            @endif
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">
                    <span id="selectedSasaran">-</span>
                </p>
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
@endif

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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Indikator Kinerja</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sasaran Strategis</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capaian</th>
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