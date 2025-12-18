<!-- Filter Data -->
<div class="bg-white rounded-2xl border border-gray-200 p-4 mb-6 filter-container">
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
                <option value="" selected>Pilih Tahun</option>
                @php
                    $maxYear = 2030;
                    $minYear = 2025;
                @endphp
                @for($year = $maxYear; $year >= $minYear; $year--)
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
        <h2 class="text-lg font-semibold text-gray-900">Data Perkara Hukum</h2>
        <div class="flex items-center space-x-4">
            <p class="text-gray-600 text-sm">Total: <span id="totalData">{{ $data->count() }}</span> data</p>
            <button id="toggleAll" class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors duration-150">Buka Semua</button>
        </div>
    </div>
    
    @if($data->count() > 0)
    <div class="divide-y divide-gray-100" id="dataContainer">
        @foreach($data as $index => $item)
        <div class="accordion-item group hover:bg-gray-50 transition-colors duration-200" 
             data-bulan="{{ $item->bulan }}" 
             data-tahun="{{ $item->tahun }}" 
             data-realisasi="{{ $item->realisasi }}" 
             data-capaian="{{ $item->capaian }}"
             data-tipe-input="{{ $item->tipe_input }}">
            <button class="accordion-header w-full px-6 py-5 text-left">
                <div class="flex justify-between items-center">
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900 text-left">{{ $item->sasaran_strategis }}</h3>
                        <p class="text-sm text-gray-500 mt-1 text-left">{{ $item->indikator_kinerja }}</p>
                        <div class="flex items-center mt-2 space-x-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ \Carbon\Carbon::createFromDate($item->tahun, $item->bulan, 1)->translatedFormat('F Y') }}
                            </span>
                            <span class="text-xs text-gray-500">Target: {{ $item->formatted_target }}</span>
                            <span class="text-xs text-gray-500">{{ $item->getSafeLabelInput1() }}: {{ $item->input_1 }}</span>
                            
                            @if($item->tipe_input == 'dua_input' && $item->input_2 !== null)
                                <span class="text-xs text-gray-500">{{ $item->getSafeLabelInput2() }}: {{ $item->input_2 }}</span>
                            @endif
                            
                            @if($item->capaian)
                                <span class="text-xs font-medium @if(floatval($item->capaian) >= 100) text-green-600 @elseif(floatval($item->capaian) >= 80) text-yellow-600 @else text-red-600 @endif">
                                    Capaian: {{ number_format(floatval($item->capaian), 2) }}%
                                </span>
                            @endif
                            
                            @if($item->hambatan || $item->rekomendasi || $item->tindak_lanjut || $item->keberhasilan)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Analisis
                                </span>
                            @endif
                            
                            <!-- Badge Tipe Input -->
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->tipe_input == 'dua_input' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ $item->tipe_input == 'dua_input' ? 'Dua Input' : 'Satu Input' }}
                            </span>
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
                    <dl class="grid grid-cols-1 md:grid-cols-{{ $item->tipe_input == 'dua_input' ? '4' : '3' }} gap-4">
                        @if($item->tipe_input == 'dua_input')
                        <!-- Data Input untuk Dua Input -->
                        <div>
                            <dt class="text-xs text-gray-500 font-medium">Data Input</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $item->getSafeLabelInput1() }}</dd>
                            <dd class="text-sm text-gray-900 mt-1 font-semibold">{{ $item->input_1 }}</dd>
                            <dd class="text-sm text-gray-900 mt-1">{{ $item->getSafeLabelInput2() }}</dd>
                            <dd class="text-sm text-gray-900 mt-1 font-semibold">{{ $item->input_2 }}</dd>
                        </div>
                        @else
                        <!-- Data Input untuk Satu Input -->
                        <div>
                            <dt class="text-xs text-gray-500 font-medium">Data Input</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $item->getSafeLabelInput1() }}</dd>
                            <dd class="text-sm text-gray-900 mt-1 font-semibold">{{ $item->input_1 }}</dd>
                        </div>
                        @endif
                        
                        <!-- Target -->
                        <div>
                            <dt class="text-xs text-gray-500 font-medium">Target</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                {{ $item->formatted_target }}
                            </dd>
                        </div>
                        
                        @if($item->tipe_input == 'dua_input')
                        <!-- Realisasi (hanya untuk dua input) -->
                        <div>
                            <dt class="text-xs text-gray-500 font-medium">Realisasi</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                @if($item->realisasi)
                                    @php
                                        $realisasi = is_numeric($item->realisasi) ? floatval($item->realisasi) : 0;
                                    @endphp
                                    <span class="font-medium">{{ number_format($realisasi, 2) }}%</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </dd>
                        </div>
                        @endif
                        
                        <!-- Capaian -->
                        <div>
                            <dt class="text-xs text-gray-500 font-medium">Capaian</dt>
                            <dd class="text-sm text-gray-900 mt-1">
                                @if($item->capaian)
                                    @php
                                        $capaian = is_numeric($item->capaian) ? floatval($item->capaian) : 0;
                                        $status_capaian = $capaian >= 100 ? 'Tercapai' : ($capaian >= 80 ? 'Hampir Tercapai' : 'Belum Tercapai');
                                        $status_color = $capaian >= 100 ? 'green' : ($capaian >= 80 ? 'yellow' : 'red');
                                        $status_class = $capaian >= 100 ? 'text-green-600' : ($capaian >= 80 ? 'text-yellow-600' : 'text-red-600');
                                        $bg_class = $capaian >= 100 ? 'bg-green-100 text-green-800' : ($capaian >= 80 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                                    @endphp
                                    <div class="flex flex-col">
                                        <span class="font-semibold {{ $status_class }}">
                                            {{ number_format($capaian, 2) }}%
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bg_class }} mt-1">
                                            {{ $status_capaian }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                    
                    <!-- Progress Bar Capaian -->
                    @if($item->capaian)
                    @php
                        $capaian = is_numeric($item->capaian) ? floatval($item->capaian) : 0;
                        $progress_width = min($capaian, 100);
                        $progress_color = $capaian >= 100 ? 'bg-green-500' : ($capaian >= 80 ? 'bg-yellow-500' : 'bg-red-500');
                    @endphp
                    <div class="mt-4">
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>Capaian Progress</span>
                            <span>{{ number_format($capaian, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full {{ $progress_color }}" 
                                 style="width: {{ $progress_width }}%">
                            </div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span>0%</span>
                            <span>100%</span>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- TABEL ANALISIS DAN EVALUASI -->
                @if($item->hambatan || $item->rekomendasi || $item->tindak_lanjut || $item->keberhasilan)
                <div class="mt-6 border-t border-gray-200 pt-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-4">Analisis dan Evaluasi</h4>
                    
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Analisis</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if($item->hambatan)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                </svg>
                                                Hambatan yang Dihadapi
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 whitespace-pre-line">
                                            {{ $item->hambatan }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Diidentifikasi
                                            </span>
                                        </td>
                                    </tr>
                                    @endif
                                    
                                    @if($item->rekomendasi)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                                Rekomendasi Perbaikan
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 whitespace-pre-line">
                                            {{ $item->rekomendasi }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Disarankan
                                            </span>
                                        </td>
                                    </tr>
                                    @endif
                                    
                                    @if($item->tindak_lanjut)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                                Tindak Lanjut yang Dilakukan
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 whitespace-pre-line">
                                            {{ $item->tindak_lanjut }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Diterapkan
                                            </span>
                                        </td>
                                    </tr>
                                    @endif
                                    
                                    @if($item->keberhasilan)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                                Keberhasilan yang Dicapai
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 whitespace-pre-line">
                                            {{ $item->keberhasilan }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Tercapai
                                            </span>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Ringkasan Analisis -->
                        <div class="mt-4 bg-white rounded-lg border border-gray-200 p-4">
                            <h5 class="text-xs font-medium text-gray-700 mb-2">Ringkasan Analisis</h5>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="text-center">
                                    <div class="text-lg font-semibold @if($item->hambatan) text-red-600 @else text-gray-300 @endif">
                                        {{ $item->hambatan ? '✓' : '-' }}
                                    </div>
                                    <div class="text-xs text-gray-500">Hambatan</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-semibold @if($item->rekomendasi) text-blue-600 @else text-gray-300 @endif">
                                        {{ $item->rekomendasi ? '✓' : '-' }}
                                    </div>
                                    <div class="text-xs text-gray-500">Rekomendasi</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-semibold @if($item->tindak_lanjut) text-green-600 @else text-gray-300 @endif">
                                        {{ $item->tindak_lanjut ? '✓' : '-' }}
                                    </div>
                                    <div class="text-xs text-gray-500">Tindak Lanjut</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-semibold @if($item->keberhasilan) text-yellow-600 @else text-gray-300 @endif">
                                        {{ $item->keberhasilan ? '✓' : '-' }}
                                    </div>
                                    <div class="text-xs text-gray-500">Keberhasilan</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="mt-5 pt-4 border-t border-gray-200 flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        Terakhir diupdate: {{ $item->updated_at ? $item->updated_at->format('d/m/Y H:i') : '-' }}
                        @if($item->hambatan || $item->rekomendasi || $item->tindak_lanjut || $item->keberhasilan)
                            <span class="ml-2 inline-flex items-center text-purple-600">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Memiliki Analisis
                            </span>
                        @endif
                        <span class="ml-2 inline-flex items-center {{ $item->tipe_input == 'dua_input' ? 'text-blue-600' : 'text-green-600' }}">
                            {{ $item->tipe_input == 'dua_input' ? 'Dua Input' : 'Satu Input' }}
                        </span>
                    </div>
                    <div class="flex space-x-3">
                        @if(auth()->user()->isSuperAdmin() || auth()->user()->role == 'hukum')
                        <button type="button" class="text-blue-600 hover:text-blue-800 text-sm font-medium edit-btn transition-colors duration-150" 
                                data-id="{{ $item->id }}"
                                data-jenis="hukum"
                                data-sasaran="{{ $item->sasaran_strategis }}"
                                data-indikator="{{ $item->indikator_kinerja }}"
                                data-target="{{ $item->target }}"
                                data-rumus="{{ $item->rumus }}"
                                data-label-input-1="{{ $item->label_input_1 }}"
                                data-label-input-2="{{ $item->label_input_2 }}"
                                data-input-1="{{ $item->input_1 }}"
                                data-input-2="{{ $item->input_2 }}"
                                data-bulan="{{ $item->bulan }}"
                                data-tahun="{{ $item->tahun }}"
                                data-hambatan="{{ $item->hambatan }}"
                                data-rekomendasi="{{ $item->rekomendasi }}"
                                data-tindak-lanjut="{{ $item->tindak_lanjut }}"
                                data-keberhasilan="{{ $item->keberhasilan }}"
                                data-user-type="{{ auth()->user()->isSuperAdmin() ? 'superadmin' : 'admin' }}">
                            Edit
                        </button>
                        @endif
                        
                        @if(auth()->user()->isSuperAdmin())
                        <form action="{{ route('hukum.destroy', $item->id) }}" method="POST" class="inline delete-form">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="jenis" value="hukum">
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors duration-150" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                Hapus
                            </button>
                        </form>
                        @endif
                    </div>
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

<script>
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
</script>