@extends('layouts.app')

@section('title', 'Tipikor - E-SIDAK')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Perkara Tipikor (Tindak Pidana Korupsi)</h1>
            <p class="text-gray-600 mt-1">Kelola data perkara Tipikor dan capaian kinerja</p>
        </div>
        @if(auth()->user()->isSuperAdmin())
            <span class="bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg text-sm font-medium border border-blue-100">Super Admin</span>
        @else
            <span class="bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-lg text-sm font-medium border border-emerald-100">Admin Tipikor</span>
        @endif
    </div>

    <!-- Include Navigation -->
    @include('admin.tipikor.partials.nav_tipikor')

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
            <div class="mt-4">
                <div class="-mx-4 -my-1.5 flex">
                    <button type="button" class="ml-3 px-2 py-1.5 rounded-md text-sm font-medium text-red-800 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-150" onclick="this.parentElement.parentElement.style.display='none'">
                        Tutup
                    </button>
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
        @include('admin.tipikor.partials.dataperkara')
    </div>

    <!-- Tab Content: Input Data 1 (Dua Input) -->
    <div id="inputData1Content" class="tab-content hidden">
        @include('admin.tipikor.partials.inputdata2input')
    </div>

    <!-- Tab Content: Input Data 2 (Satu Input) -->
    <div id="inputData2Content" class="tab-content hidden">
        @include('admin.tipikor.partials.inputdata1input')
    </div>

    <!-- Tab Content: Sasaran Strategis 2 Input (Super Admin Only) -->
    @if(auth()->user()->isSuperAdmin())
    <div id="sasaranContent2Input" class="tab-content hidden">
        @include('admin.tipikor.partials.sasaranstrategis2input')
    </div>
    @endif

    <!-- Tab Content: Sasaran Strategis 1 Input (Super Admin Only) -->
    @if(auth()->user()->isSuperAdmin())
    <div id="sasaranContent1Input" class="tab-content hidden">
        @include('admin.tipikor.partials.sasaranstrategis1input')
    </div>
    @endif

    <!-- Tab Content: Lampiran PDF -->
    <div id="lampiranContent" class="tab-content hidden">
        @include('admin.tipikor.partials.lampiran')
    </div>

    <!-- Modal Edit (Dynamic Form) -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-40 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300 flex items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl border border-gray-200 w-full max-w-2xl transform transition-all duration-300 scale-95">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4" id="editModalTitle">Edit Data Perkara Tipikor</h3>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="jenis" id="edit_jenis">
                    <input type="hidden" name="id" id="edit_id">
                    
                    <div class="space-y-4 mb-4" style="max-height: 60vh; overflow-y: auto;">
                        <!-- Dynamic form fields akan diisi oleh JavaScript -->
                        <div id="editFormFields">
                            <!-- Form fields akan di-generate berdasarkan user type -->
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
        // CSRF token untuk AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
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
                else if (tabId === 'inputData1Tab') contentId = 'inputData1Content';
                else if (tabId === 'inputData2Tab') contentId = 'inputData2Content';
                else if (tabId === 'sasaranTab2Input') contentId = 'sasaranContent2Input';
                else if (tabId === 'sasaranTab1Input') contentId = 'sasaranContent1Input';
                else if (tabId === 'lampiranTab') {
                    contentId = 'lampiranContent';
                    loadLampiranData();
                }
                
                if (contentId) {
                    document.getElementById(contentId).classList.remove('hidden');
                    document.getElementById(contentId).classList.add('active');
                }
            });
        });
        
        // Helper function untuk generate bulan options
        function generateMonthOptions(selectedMonth) {
            const months = [
                { value: '1', text: 'Januari' },
                { value: '2', text: 'Februari' },
                { value: '3', text: 'Maret' },
                { value: '4', text: 'April' },
                { value: '5', text: 'Mei' },
                { value: '6', text: 'Juni' },
                { value: '7', text: 'Juli' },
                { value: '8', text: 'Agustus' },
                { value: '9', text: 'September' },
                { value: '10', text: 'Oktober' },
                { value: '11', text: 'November' },
                { value: '12', text: 'Desember' }
            ];
            
            return months.map(month => 
                `<option value="${month.value}" ${month.value === selectedMonth ? 'selected' : ''}>${month.text}</option>`
            ).join('');
        }
        
        // Fungsi untuk hitung di modal edit
        function editHitungFunction() {
            const input1 = document.getElementById('edit_input_1');
            const input2 = document.getElementById('edit_input_2');
            
            if (!input1 || !input2) {
                console.error('Input elements not found');
                return;
            }
            
            const perkaraDiselesaikan = parseFloat(input1.value) || 0;
            const perkaraTepatWaktu = parseFloat(input2.value) || 0;
            let target = 0;
            
            // Cek apakah user adalah superadmin atau admin biasa
            const targetInput = document.getElementById('edit_target');
            if (targetInput && targetInput.tagName === 'INPUT') {
                // Super Admin
                target = parseFloat(targetInput.value) || 0;
            } else {
                // Admin Biasa - cari hidden field dengan name="target"
                const targetHidden = document.querySelector('input[name="target"][type="hidden"]');
                target = targetHidden ? parseFloat(targetHidden.value) || 0 : 0;
            }
            
            if (perkaraDiselesaikan === 0) {
                alert('Jumlah perkara Tipikor diselesaikan tidak boleh 0');
                return;
            }
            
            // Hitung realisasi
            const realisasi = (perkaraTepatWaktu / perkaraDiselesaikan) * 100;
            
            // Hitung capaian (Realisasi / Target * 100)
            const capaian = target > 0 ? (realisasi / target) * 100 : 0;
            
            // Tentukan status capaian
            let status = '';
            let statusClass = '';
            
            if (capaian >= 100) {
                status = 'Tercapai';
                statusClass = 'text-green-600 border-green-300 bg-green-50';
            } else if (capaian >= 80) {
                status = 'Hampir Tercapai';
                statusClass = 'text-yellow-600 border-yellow-300 bg-yellow-50';
            } else {
                status = 'Belum Tercapai';
                statusClass = 'text-red-600 border-red-300 bg-red-50';
            }
            
            // Update tampilan
            const realisasiResult = document.getElementById('edit_realisasi_result');
            const capaianResult = document.getElementById('edit_capaian_result');
            const statusResult = document.getElementById('edit_status_capaian_result');
            const progressBar = document.getElementById('edit_progress_bar');
            const progressPercent = document.getElementById('edit_progress_percent');
            
            if (realisasiResult) realisasiResult.value = realisasi.toFixed(2) + '%';
            if (capaianResult) capaianResult.value = capaian.toFixed(2) + '%';
            if (statusResult) {
                statusResult.value = status;
                statusResult.className = `w-full px-3 py-2 border rounded-lg font-medium ${statusClass}`;
            }
            
            // Update progress bar
            const progressWidth = Math.min(capaian, 100);
            if (progressBar) {
                progressBar.style.width = progressWidth + '%';
            }
            if (progressPercent) {
                progressPercent.textContent = progressWidth.toFixed(1) + '%';
            }
            
            // Update progress bar color
            if (progressBar) {
                if (capaian >= 100) {
                    progressBar.className = 'h-2 rounded-full bg-green-500';
                } else if (capaian >= 80) {
                    progressBar.className = 'h-2 rounded-full bg-yellow-500';
                } else {
                    progressBar.className = 'h-2 rounded-full bg-red-500';
                }
            }
        }
        
        // Attach event listener untuk tombol hitung di modal edit
        function attachEditHitungListener() {
            const editHitungBtn = document.getElementById('edit_hitungBtn');
            if (editHitungBtn) {
                editHitungBtn.addEventListener('click', function() {
                    editHitungFunction();
                });
            }
        }
        
        // Edit button functionality dengan dynamic form generation
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
                const input1 = this.getAttribute('data-input-1');
                const input2 = this.getAttribute('data-input-2');
                const hambatan = this.getAttribute('data-hambatan');
                const rekomendasi = this.getAttribute('data-rekomendasi');
                const tindakLanjut = this.getAttribute('data-tindak-lanjut');
                const keberhasilan = this.getAttribute('data-keberhasilan');
                const userType = this.getAttribute('data-user-type');
                
                let formFields = '';
                
                if (userType === 'superadmin') {
                    // Form untuk Super Admin
                    formFields = `
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sasaran Strategis</label>
                            <input type="text" name="sasaran_strategis" id="edit_sasaran" required
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   value="${sasaran}">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Indikator Kinerja</label>
                            <input type="text" name="indikator_kinerja" id="edit_indikator" required
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   value="${indikator}">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Target (%)</label>
                            <input type="number" name="target" id="edit_target" step="0.01" required min="0" max="100"
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   value="${target}">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rumus</label>
                            <input type="text" name="rumus" id="edit_rumus" required
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                   value="${rumus}">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Label Input 1</label>
                                <input type="text" name="label_input_1" id="edit_label_input_1" required
                                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       value="${labelInput1}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Label Input 2</label>
                                <input type="text" name="label_input_2" id="edit_label_input_2" required
                                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       value="${labelInput2}">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">${labelInput1}</label>
                                <input type="number" name="input_1" id="edit_input_1" required min="0"
                                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       value="${input1}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">${labelInput2}</label>
                                <input type="number" name="input_2" id="edit_input_2" required min="0"
                                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       value="${input2}">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                                <select name="bulan" id="edit_bulan" required
                                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white">
                                    <option value="">Pilih Bulan</option>
                                    ${generateMonthOptions(bulan)}
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                                <input type="number" name="tahun" id="edit_tahun" required min="2020"
                                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       value="${tahun}">
                            </div>
                        </div>
                    `;
                } else {
                    // Form untuk Admin Biasa
                    formFields = `
                        <div class="bg-gray-50 rounded-xl p-4 mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Informasi Sasaran Strategis</h4>
                            <div class="space-y-2">
                                <div>
                                    <span class="text-xs text-gray-500">Sasaran Strategis:</span>
                                    <p class="text-sm font-medium text-gray-900">${sasaran}</p>
                                    <input type="hidden" name="sasaran_strategis" value="${sasaran}">
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Indikator Kinerja:</span>
                                    <p class="text-sm font-medium text-gray-900">${indikator}</p>
                                    <input type="hidden" name="indikator_kinerja" value="${indikator}">
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Target:</span>
                                    <p class="text-sm font-medium text-gray-900">${target}%</p>
                                    <input type="hidden" name="target" value="${target}">
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Rumus:</span>
                                    <p class="text-sm font-medium text-gray-900">${rumus}</p>
                                    <input type="hidden" name="rumus" value="${rumus}">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">${labelInput1}</label>
                                <input type="number" name="input_1" id="edit_input_1" required min="0"
                                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       value="${input1}">
                                <input type="hidden" name="label_input_1" value="${labelInput1}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">${labelInput2}</label>
                                <input type="number" name="input_2" id="edit_input_2" required min="0"
                                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       value="${input2}">
                                <input type="hidden" name="label_input_2" value="${labelInput2}">
                            </div>
                        </div>

                        <input type="hidden" name="bulan" value="${bulan}">
                        <input type="hidden" name="tahun" value="${tahun}">
                    `;
                }
                
                // Bagian form analisis dan hasil perhitungan
                formFields += `
                    <!-- HASIL PERHITUNGAN EDIT -->
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-sm font-medium text-gray-700">Hasil Perhitungan</h4>
                            <button type="button" id="edit_hitungBtn"
                                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition duration-200 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                Hitung
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs text-gray-500 font-medium mb-1">Realisasi</label>
                                <input type="text" id="edit_realisasi_result" readonly
                                       class="w-full px-3 py-2 border border-blue-300 rounded-lg bg-white text-gray-900 font-medium">
                            </div>

                            <div>
                                <label class="block text-xs text-gray-500 font-medium mb-1">Capaian</label>
                                <input type="text" id="edit_capaian_result" readonly
                                       class="w-full px-3 py-2 border border-blue-300 rounded-lg bg-white text-gray-900 font-medium">
                            </div>

                            <div>
                                <label class="block text-xs text-gray-500 font-medium mb-1">Status Capaian</label>
                                <input type="text" id="edit_status_capaian_result" readonly
                                       class="w-full px-3 py-2 border border-blue-300 rounded-lg bg-white text-gray-900">
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="mt-4">
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Progress Capaian</span>
                                <span id="edit_progress_percent">0%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="edit_progress_bar" class="h-2 rounded-full bg-blue-500" style="width: 0%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>0%</span>
                                <span>100%</span>
                            </div>
                        </div>
                    </div>

                    <!-- FORM ANALISIS -->
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                        <h4 class="text-sm font-medium text-gray-700 mb-4">Analisis dan Evaluasi</h4>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Hambatan yang Dihadapi
                            </label>
                            <textarea name="hambatan" id="edit_hambatan" rows="2"
                                      class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                                      placeholder="Jelaskan hambatan atau kendala yang dihadapi">${hambatan || ''}</textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Rekomendasi Perbaikan
                            </label>
                            <textarea name="rekomendasi" id="edit_rekomendasi" rows="2"
                                      class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                                      placeholder="Berikan rekomendasi untuk perbaikan">${rekomendasi || ''}</textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tindak Lanjut yang Dilakukan
                            </label>
                            <textarea name="tindak_lanjut" id="edit_tindak_lanjut" rows="2"
                                      class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                                      placeholder="Jelaskan tindak lanjut yang akan atau telah dilakukan">${tindakLanjut || ''}</textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Keberhasilan yang Dicapai
                            </label>
                            <textarea name="keberhasilan" id="edit_keberhasilan" rows="2"
                                      class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                                      placeholder="Jelaskan keberhasilan atau pencapaian positif">${keberhasilan || ''}</textarea>
                        </div>
                    </div>
                `;
                
                // Isi form fields
                document.getElementById('editFormFields').innerHTML = formFields;
                
                // Set nilai lain
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_jenis').value = jenis;
                document.getElementById('editForm').action = `/perkara/${id}`;
                
                // Set modal title
                document.getElementById('editModalTitle').textContent = userType === 'superadmin' 
                    ? 'Edit Data Perkara Tipikor (Super Admin)' 
                    : 'Edit Data Perkara Tipikor (Admin)';
                
                // Tampilkan modal
                document.getElementById('editModal').classList.remove('hidden');
                
                // Re-attach event listener untuk tombol hitung
                attachEditHitungListener();
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
        
        // ==================== FUNGSIONALITAS LAMPIRAN ====================
        // Fungsi untuk menghitung capaian satu input
        async function hitungCapaianSatuInput(input1, target) {
            try {
                const response = await fetch('/perkara/calculate-satu-input', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        input_1: input1,
                        target: target
                    })
                });
                
                return await response.json();
            } catch (error) {
                console.error('Error:', error);
                return null;
            }
        }
        
        // Fungsi untuk memuat data lampiran
        function loadLampiranData() {
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
            let url = '/tipikor/lampiran';
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
                    
                    if (data.length === 0) {
                        empty.classList.remove('hidden');
                        return;
                    }
                    
                    tableBody.innerHTML = data.map(lampiran => {
                        const isSuperAdmin = {{ auth()->user()->isSuperAdmin() ? 'true' : 'false' }};
                        const canEdit = isSuperAdmin;
                        const canDelete = isSuperAdmin || {{ auth()->user()->id }} == lampiran.user_id;
                        
                        const capaian = lampiran.tipikor ? lampiran.tipikor.capaian : null;
                        let capaianDisplay = '-';
                        let capaianClass = '';
                        
                        if (capaian) {
                            capaianDisplay = parseFloat(capaian).toFixed(2) + '%';
                            if (capaian >= 100) {
                                capaianClass = 'bg-green-100 text-green-800';
                            } else if (capaian >= 80) {
                                capaianClass = 'bg-yellow-100 text-yellow-800';
                            } else {
                                capaianClass = 'bg-red-100 text-red-800';
                            }
                        }
                        
                        return `
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${lampiran.id}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <a href="/tipikor/lampiran/${lampiran.id}/download" 
                                       class="text-blue-600 hover:text-blue-900 hover:underline"
                                       target="_blank">
                                        ${lampiran.original_name}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${lampiran.tipikor ? lampiran.tipikor.indikator_kinerja : '-'}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${lampiran.tipikor ? lampiran.tipikor.sasaran_strategis : '-'}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${lampiran.tipikor ? lampiran.tipikor.nama_bulan + ' ' + lampiran.tipikor.tahun : '-'}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    ${capaian ? `
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${capaianClass}">
                                            ${capaianDisplay}
                                        </span>
                                    ` : '-'}
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
                    
                    // Tambahkan event listener untuk tombol edit dan hapus
                    document.querySelectorAll('.edit-lampiran-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            const nama = this.getAttribute('data-nama');
                            alert('Edit functionality belum diimplementasikan');
                        });
                    });
                    
                    document.querySelectorAll('.delete-lampiran-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = this.getAttribute('data-id');
                            if (confirm('Apakah Anda yakin ingin menghapus lampiran ini?')) {
                                deleteLampiran(id);
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
        
        // Fungsi untuk menghapus lampiran
        function deleteLampiran(id) {
            fetch(`/tipikor/lampiran/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-HTTP-Method-Override': 'DELETE'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Lampiran berhasil dihapus!');
                    loadLampiranData();
                } else {
                    alert('Error: ' + (data.error || 'Gagal menghapus lampiran'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus lampiran');
            });
        }
        
        // Fungsi untuk format ukuran file
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
        
        // Event listener untuk filter lampiran
        document.getElementById('filterLampiranBtn')?.addEventListener('click', function() {
            const bulan = document.getElementById('lampiranFilterBulan').value;
            const tahun = document.getElementById('lampiranFilterTahun').value;
            const indikator = document.getElementById('lampiranFilterIndikator').value.toLowerCase();
            
            const options = document.querySelectorAll('#lampiranTipikorSelect option');
            options.forEach(option => {
                if (option.value === '') return;
                
                const optionBulan = option.getAttribute('data-bulan');
                const optionTahun = option.getAttribute('data-tahun');
                const optionIndikator = option.getAttribute('data-indikator').toLowerCase();
                
                const matchBulan = !bulan || bulan === optionBulan;
                const matchTahun = !tahun || tahun === optionTahun;
                const matchIndikator = !indikator || optionIndikator.includes(indikator);
                
                if (matchBulan && matchTahun && matchIndikator) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                    if (option.selected) {
                        option.selected = false;
                        document.getElementById('lampiranTipikorSelect').value = '';
                        document.getElementById('selectedSasaran').textContent = '-';
                    }
                }
            });
        });
        
        // Event listener untuk menampilkan sasaran strategis saat memilih data
        document.getElementById('lampiranTipikorSelect')?.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const sasaran = selectedOption.getAttribute('data-sasaran');
            document.getElementById('selectedSasaran').textContent = sasaran || '-';
        });
        
        // Event listener untuk form upload lampiran
        document.getElementById('uploadLampiranForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const uploadBtn = document.getElementById('uploadBtn');
            const originalText = uploadBtn.innerHTML;
            
            // Show loading state
            uploadBtn.innerHTML = `
                <div class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></div>
                Mengupload...
            `;
            uploadBtn.disabled = true;
            
            fetch('/tipikor/lampiran/upload', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Lampiran berhasil diupload!');
                    this.reset();
                    document.getElementById('selectedSasaran').textContent = '-';
                    loadLampiranData();
                } else {
                    alert('Error: ' + (data.error || 'Gagal mengupload lampiran'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupload lampiran');
            })
            .finally(() => {
                // Reset button state
                uploadBtn.innerHTML = originalText;
                uploadBtn.disabled = false;
            });
        });
        
        // Event listener untuk filter daftar lampiran
        document.getElementById('daftarFilterBtn')?.addEventListener('click', function() {
            loadLampiranData();
        });
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

    .filter-container {
        transition: all 0.3s ease;
    }

    .filter-container:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    #cariBtn, #hitungBtn1, #hitungBtn2 {
        transition: all 0.3s ease;
    }

    #cariBtn:hover, #hitungBtn1:hover, #hitungBtn2:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3), 0 2px 4px -1px rgba(59, 130, 246, 0.2);
    }

    #cariBtn:active, #hitungBtn1:active, #hitungBtn2:active {
        transform: translateY(0);
    }

    #progress_bar_1, #progress_bar_2, #edit_progress_bar {
        transition: width 0.5s ease;
    }

    textarea {
        min-height: 80px;
    }

    /* Responsive table */
    @media (max-width: 768px) {
        .overflow-x-auto {
            overflow-x: auto;
        }
        
        .min-w-full {
            min-width: 640px;
        }
    }
    </style>
@endsection