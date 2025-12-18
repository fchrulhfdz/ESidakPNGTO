<div class="bg-white rounded-2xl border border-gray-200 p-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-6">Input Data Perhitungan (Satu Input)</h2>
    
    <form id="formInputData2" action="{{ route('store.perkara') }}" method="POST">
        @csrf
        <input type="hidden" name="jenis" value="phi">
        <input type="hidden" name="tipe_input" value="satu_input">
        <input type="hidden" name="sasaran_strategis" id="sasaran_hidden_2" value="{{ old('sasaran_strategis') }}">
        <input type="hidden" name="indikator_kinerja" id="indikator_hidden_2" value="{{ old('indikator_kinerja') }}">
        <input type="hidden" name="target" id="target_hidden_2" value="{{ old('target') }}">
        <input type="hidden" name="rumus" id="rumus_hidden_2" value="{{ old('rumus') }}">
        <input type="hidden" name="bulan" id="bulan_hidden_2" value="{{ old('bulan') }}">
        <input type="hidden" name="tahun" id="tahun_hidden_2" value="{{ old('tahun') }}">
        <input type="hidden" name="label_input_1" id="label_input_1_hidden_2" value="{{ old('label_input_1') }}">
        <input type="hidden" name="realisasi" id="realisasi_hidden_2" value="{{ old('realisasi') }}">
        <input type="hidden" name="capaian" id="capaian_hidden_2" value="{{ old('capaian') }}">
        <input type="hidden" name="status_capaian" id="status_capaian_hidden_2" value="{{ old('status_capaian') }}">

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <select name="bulan" id="bulan_2" required
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
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <input type="number" name="tahun" id="tahun_2" required min="2000" max="2100"
                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                           value="{{ old('tahun', date('Y')) }}">
                </div>
            </div>

            <!-- SASARAN STRATEGIS (ditampilkan otomatis) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sasaran Strategis</label>
                <input type="text" id="sasaran_display_2" readonly
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-gray-50"
                       value="{{ old('sasaran_strategis') }}"
                       placeholder="Sasaran strategis akan muncul setelah memilih indikator kinerja">
            </div>

            <!-- DROPDOWN INDIKATOR KINERJA - Hanya tampilkan yang tipe_input satu_input -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Indikator Kinerja</label>
                <select id="pilihIndikator_2" required
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white">
                    <option value="">Pilih Indikator Kinerja (Satu Input)</option>
                    @foreach($sasaran_strategis_satu_input as $item)
                        <option value="{{ $item->id }}" 
                                data-sasaran="{{ $item->sasaran_strategis }}"
                                data-indikator="{{ $item->indikator_kinerja }}"
                                data-target="{{ $item->target }}"
                                data-rumus="{{ $item->rumus }}"
                                data-bulan="{{ $item->bulan }}"
                                data-tahun="{{ $item->tahun }}"
                                data-label-input-1="{{ $item->label_input_1 }}"
                                data-tipe-input="satu_input"
                                @if(old('indikator_kinerja') == $item->indikator_kinerja) selected @endif>
                            {{ Str::limit($item->indikator_kinerja, 70) }}
                        </option>
                    @endforeach
                </select>
                @if($sasaran_strategis_satu_input->isEmpty())
                    <p class="text-xs text-red-500 mt-1">Belum ada sasaran strategis untuk tipe input satu input. Silakan tambah di menu Sasaran Strategis (1 Input).</p>
                @endif
            </div>

            <!-- TARGET -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Target</label>
                <input type="text" id="target_display_2" readonly
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-gray-50"
                       value="{{ old('target') }}"
                       placeholder="Target akan muncul setelah memilih indikator kinerja">
                <p class="text-xs text-gray-500 mt-1">Dalam satuan yang sesuai (misal: 100 untuk 100 perkara)</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2" id="label_input_1_display_2">
                    Label input
                </label>
                <input type="number" name="input_1" id="input_1_2" required min="0"
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                       value="{{ old('input_1') }}">
            </div>

            <!-- HASIL PERHITUNGAN -->
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-sm font-medium text-gray-700">Hasil Perhitungan</h4>
                    <button type="button" id="hitungBtn2" 
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
                        <input type="text" id="realisasi_result_2" readonly
                               class="w-full px-3 py-2 border border-blue-300 rounded-lg bg-white text-gray-900 font-medium"
                               value="-" placeholder="Tidak berlaku untuk satu input">
                    </div>
                    
                    <div>
                        <label class="block text-xs text-gray-500 font-medium mb-1">Capaian</label>
                        <input type="text" id="capaian_result_2" readonly
                               class="w-full px-3 py-2 border border-blue-300 rounded-lg bg-white text-gray-900 font-medium">
                    </div>
                    
                    <div>
                        <label class="block text-xs text-gray-500 font-medium mb-1">Status Capaian</label>
                        <input type="text" id="status_capaian_result_2" readonly
                               class="w-full px-3 py-2 border border-blue-300 rounded-lg bg-white text-gray-900">
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                        <span>Progress Capaian</span>
                        <span id="progress_percent_2">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div id="progress_bar_2" class="h-2 rounded-full bg-blue-500" style="width: 0%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                        <span>0%</span>
                        <span>100%</span>
                    </div>
                </div>
            </div>

            <!-- ========== FORM ANALISIS ========== -->
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                <h4 class="text-sm font-medium text-gray-700 mb-4">Analisis dan Evaluasi</h4>
                
                <div class="grid grid-cols-1 gap-6">
                    <!-- Hambatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Hambatan yang Dihadapi
                        </label>
                        <textarea name="hambatan" id="hambatan_2" rows="3"
                                  class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white resize-none"
                                  placeholder="Jelaskan hambatan atau kendala yang dihadapi dalam penyelesaian perkara">{{ old('hambatan') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Opsional. Maksimal 2000 karakter.</p>
                    </div>
                    
                    <!-- Rekomendasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Rekomendasi Perbaikan
                        </label>
                        <textarea name="rekomendasi" id="rekomendasi_2" rows="3"
                                  class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white resize-none"
                                  placeholder="Berikan rekomendasi untuk perbaikan ke depan">{{ old('rekomendasi') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Opsional. Maksimal 2000 karakter.</p>
                    </div>
                    
                    <!-- Tindak Lanjut -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tindak Lanjut yang Dilakukan
                        </label>
                        <textarea name="tindak_lanjut" id="tindak_lanjut_2" rows="3"
                                  class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white resize-none"
                                  placeholder="Jelaskan tindak lanjut yang akan atau telah dilakukan">{{ old('tindak_lanjut') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Opsional. Maksimal 2000 karakter.</p>
                    </div>
                    
                    <!-- Keberhasilan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Keberhasilan yang Dicapai
                        </label>
                        <textarea name="keberhasilan" id="keberhasilan_2" rows="3"
                                  class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white resize-none"
                                  placeholder="Jelaskan keberhasilan atau pencapaian positif yang diraih">{{ old('keberhasilan') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Opsional. Maksimal 2000 karakter.</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-2 space-x-3">
                <button type="button" id="resetBtn2" 
                        class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200 font-medium">
                    Reset
                </button>
                <button type="submit" id="simpanBtn2"
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

<script>
// Setup untuk form input data satu input
function setupInputDataFormSatuInput(formNumber) {
    const pilihIndikator = document.getElementById(`pilihIndikator_${formNumber}`);
    const sasaranHidden = document.getElementById(`sasaran_hidden_${formNumber}`);
    const indikatorHidden = document.getElementById(`indikator_hidden_${formNumber}`);
    const targetHidden = document.getElementById(`target_hidden_${formNumber}`);
    const rumusHidden = document.getElementById(`rumus_hidden_${formNumber}`);
    const bulanHidden = document.getElementById(`bulan_hidden_${formNumber}`);
    const tahunHidden = document.getElementById(`tahun_hidden_${formNumber}`);
    const labelInput1Hidden = document.getElementById(`label_input_1_hidden_${formNumber}`);
    const realisasiHidden = document.getElementById(`realisasi_hidden_${formNumber}`);
    const capaianHidden = document.getElementById(`capaian_hidden_${formNumber}`);
    const statusCapaianHidden = document.getElementById(`status_capaian_hidden_${formNumber}`);
    const bulanDropdown = document.getElementById(`bulan_${formNumber}`);
    const tahunInput = document.getElementById(`tahun_${formNumber}`);
    const labelInput1Display = document.getElementById(`label_input_1_display_${formNumber}`);
    const sasaranDisplay = document.getElementById(`sasaran_display_${formNumber}`);
    const targetDisplay = document.getElementById(`target_display_${formNumber}`);
    const hitungBtn = document.getElementById(`hitungBtn${formNumber}`);
    const input1 = document.getElementById(`input_1_${formNumber}`);
    const resetBtn = document.getElementById(`resetBtn${formNumber}`);
    const simpanBtn = document.getElementById(`simpanBtn${formNumber}`);
    
    // Fungsi untuk filter indikator kinerja berdasarkan bulan dan tahun
    function filterIndikatorKinerja(formNum) {
        const selectedBulan = bulanDropdown?.value || '';
        const selectedTahun = tahunInput?.value || '';
        
        if (!pilihIndikator) return;
        
        const options = pilihIndikator.querySelectorAll('option');
        let hasVisibleOptions = false;
        let hasSelection = false;
        
        options.forEach(option => {
            if (option.value === '') {
                option.style.display = '';
                return;
            }
            
            const optionBulan = option.getAttribute('data-bulan');
            const optionTahun = option.getAttribute('data-tahun');
            const optionTipeInput = option.getAttribute('data-tipe-input');
            
            // Pastikan tipe input sesuai dengan form (2 = satu_input)
            const expectedTipeInput = 'satu_input';
            
            if (optionBulan === selectedBulan && optionTahun === selectedTahun && optionTipeInput === expectedTipeInput) {
                option.style.display = '';
                hasVisibleOptions = true;
                
                // Cek apakah option ini yang sedang dipilih
                if (option.selected) {
                    hasSelection = true;
                }
            } else {
                option.style.display = 'none';
                if (option.selected) {
                    option.selected = false;
                    pilihIndikator.value = '';
                    resetFormFields(formNum);
                }
            }
        });
        
        // Jika tidak ada yang terpilih, reset form
        if (!hasSelection && pilihIndikator.value) {
            resetFormFields(formNum);
        }
        
        showNoDataMessage(pilihIndikator, hasVisibleOptions, selectedBulan, selectedTahun, 'indikator kinerja');
    }
    
    // Fungsi untuk menampilkan pesan tidak ada data
    function showNoDataMessage(selectElement, hasVisibleOptions, bulan, tahun, type) {
        if (!hasVisibleOptions && bulan && tahun) {
            const existingMessage = selectElement.querySelector('.no-data-message');
            if (!existingMessage) {
                const messageOption = document.createElement('option');
                messageOption.value = '';
                messageOption.textContent = `Tidak ada ${type} untuk bulan ${bulan} tahun ${tahun}`;
                messageOption.disabled = true;
                messageOption.selected = true;
                messageOption.classList.add('no-data-message');
                selectElement.appendChild(messageOption);
            }
        } else {
            const existingMessage = selectElement.querySelector('.no-data-message');
            if (existingMessage) {
                existingMessage.remove();
            }
        }
    }
    
    // Fungsi untuk mengisi form fields dari dropdown yang dipilih
    function fillFormFields(selectedOption, formNum) {
        if (selectedOption.value) {
            sasaranHidden.value = selectedOption.getAttribute('data-sasaran');
            indikatorHidden.value = selectedOption.getAttribute('data-indikator');
            targetHidden.value = selectedOption.getAttribute('data-target');
            rumusHidden.value = selectedOption.getAttribute('data-rumus');
            
            const labelInput1Value = selectedOption.getAttribute('data-label-input-1');
            const sasaranValue = selectedOption.getAttribute('data-sasaran');
            const targetValue = selectedOption.getAttribute('data-target');
            
            // Update display fields
            if (sasaranDisplay && sasaranValue) {
                sasaranDisplay.value = sasaranValue;
            }
            if (labelInput1Display && labelInput1Value) {
                labelInput1Display.textContent = labelInput1Value;
            }
            if (targetDisplay && targetValue) {
                targetDisplay.value = targetValue;
            }
            
            if (labelInput1Hidden) labelInput1Hidden.value = labelInput1Value;
            
            const dataBulan = selectedOption.getAttribute('data-bulan');
            const dataTahun = selectedOption.getAttribute('data-tahun');
            
            bulanHidden.value = dataBulan;
            tahunHidden.value = dataTahun;
            
            if (bulanDropdown && dataBulan) {
                bulanDropdown.value = dataBulan;
            }
            if (tahunInput && dataTahun) {
                tahunInput.value = dataTahun;
            }
        } else {
            resetFormFields(formNum);
        }
    }
    
    // Fungsi untuk reset semua field form
    function resetFormFields(formNum) {
        sasaranHidden.value = '';
        indikatorHidden.value = '';
        targetHidden.value = '';
        rumusHidden.value = '';
        bulanHidden.value = '';
        tahunHidden.value = '';
        
        if (labelInput1Hidden) labelInput1Hidden.value = '';
        
        if (sasaranDisplay) sasaranDisplay.value = '';
        if (labelInput1Display) labelInput1Display.textContent = 'Label input';
        
        // Reset target display
        if (targetDisplay) targetDisplay.value = '';
        
        // Reset hasil perhitungan
        if (document.getElementById(`realisasi_result_${formNum}`)) {
            document.getElementById(`realisasi_result_${formNum}`).value = '-';
        }
        if (document.getElementById(`capaian_result_${formNum}`)) {
            document.getElementById(`capaian_result_${formNum}`).value = '';
        }
        if (document.getElementById(`status_capaian_result_${formNum}`)) {
            document.getElementById(`status_capaian_result_${formNum}`).value = '';
        }
        if (document.getElementById(`progress_percent_${formNum}`)) {
            document.getElementById(`progress_percent_${formNum}`).textContent = '0%';
        }
        if (document.getElementById(`progress_bar_${formNum}`)) {
            document.getElementById(`progress_bar_${formNum}`).style.width = '0%';
            document.getElementById(`progress_bar_${formNum}`).className = 'h-2 rounded-full bg-blue-500';
        }
        
        if (realisasiHidden) realisasiHidden.value = '';
        if (capaianHidden) capaianHidden.value = '';
        if (statusCapaianHidden) statusCapaianHidden.value = '';
        
        // Reset kolom analisis
        if (document.getElementById(`hambatan_${formNum}`)) {
            document.getElementById(`hambatan_${formNum}`).value = '';
        }
        if (document.getElementById(`rekomendasi_${formNum}`)) {
            document.getElementById(`rekomendasi_${formNum}`).value = '';
        }
        if (document.getElementById(`tindak_lanjut_${formNum}`)) {
            document.getElementById(`tindak_lanjut_${formNum}`).value = '';
        }
        if (document.getElementById(`keberhasilan_${formNum}`)) {
            document.getElementById(`keberhasilan_${formNum}`).value = '';
        }
    }
    
    // Panggil fungsi filter saat halaman dimuat
    filterIndikatorKinerja(formNumber);
    
    // Event listener untuk dropdown indikator kinerja
    if (pilihIndikator) {
        pilihIndikator.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (selectedOption.classList.contains('no-data-message')) {
                return;
            }
            
            fillFormFields(selectedOption, formNumber);
        });
    }
    
    // Event listener untuk update hidden field ketika user mengubah bulan/tahun secara manual
    if (bulanDropdown) {
        bulanDropdown.addEventListener('change', function() {
            bulanHidden.value = this.value;
            filterIndikatorKinerja(formNumber);
        });
    }
    
    if (tahunInput) {
        tahunInput.addEventListener('input', function() {
            tahunHidden.value = this.value;
            filterIndikatorKinerja(formNumber);
        });
    }
    
    // Hitung functionality dengan validasi bulan dan tahun
    if (hitungBtn) {
        hitungBtn.addEventListener('click', async function() {
            const inputValue = parseFloat(input1.value) || 0;
            const target = parseFloat(targetHidden.value) || 0;
            
            if (!bulanHidden.value || !tahunHidden.value) {
                alert('Silakan pilih bulan dan tahun terlebih dahulu');
                return;
            }
            
            if (!sasaranHidden.value) {
                alert('Sasaran strategis tidak ditemukan');
                return;
            }
            
            if (!indikatorHidden.value) {
                alert('Indikator kinerja tidak ditemukan');
                return;
            }
            
            if (target === 0) {
                alert('Target tidak boleh 0');
                return;
            }
            
            // Hitung capaian (Input / Target * 100)
            const capaian = (inputValue / target) * 100;
            
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
            document.getElementById(`realisasi_result_${formNumber}`).value = '-';
            document.getElementById(`capaian_result_${formNumber}`).value = capaian.toFixed(2) + '%';
            document.getElementById(`status_capaian_result_${formNumber}`).value = status;
            document.getElementById(`status_capaian_result_${formNumber}`).className = `w-full px-3 py-2 border rounded-lg font-medium ${statusClass}`;
            
            // Update progress bar
            const progressWidth = Math.min(capaian, 100);
            document.getElementById(`progress_bar_${formNumber}`).style.width = progressWidth + '%';
            document.getElementById(`progress_percent_${formNumber}`).textContent = progressWidth.toFixed(1) + '%';
            
            // Update progress bar color
            if (capaian >= 100) {
                document.getElementById(`progress_bar_${formNumber}`).className = 'h-2 rounded-full bg-green-500';
            } else if (capaian >= 80) {
                document.getElementById(`progress_bar_${formNumber}`).className = 'h-2 rounded-full bg-yellow-500';
            } else {
                document.getElementById(`progress_bar_${formNumber}`).className = 'h-2 rounded-full bg-red-500';
            }
            
            // Simpan nilai untuk form submission
            realisasiHidden.value = null;
            capaianHidden.value = capaian;
            statusCapaianHidden.value = status;
        });
    }
    
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            document.getElementById(`formInputData${formNumber}`).reset();
            resetFormFields(formNumber);
        });
    }
    
    // Form validation sebelum submit
    const formInputData = document.getElementById(`formInputData${formNumber}`);
    if (formInputData) {
        formInputData.addEventListener('submit', function(e) {
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
                alert('Indikator kinerja harus ada');
                return;
            }

            if (!targetHidden.value) {
                e.preventDefault();
                alert('Target harus ada');
                return;
            }

            if (!input1.value) {
                e.preventDefault();
                alert('Data input harus diisi');
                return;
            }

            if (!capaianHidden.value) {
                e.preventDefault();
                alert('Silakan klik tombol Hitung terlebih dahulu');
                return;
            }
        });
    }
}

// Inisialisasi form input data satu input
setupInputDataFormSatuInput('2');
</script>