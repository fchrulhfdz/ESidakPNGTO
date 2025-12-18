<div class="bg-white rounded-2xl border border-gray-200 p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold text-gray-900">Tambah Sasaran Strategis Baru (1 Input)</h2>
    </div>
    
    <form action="{{ route('store.perkara') }}" method="POST" id="formTambahSasaran1Input">
        @csrf
        <input type="hidden" name="jenis" value="perdata">
        <!-- Tambahkan input hidden untuk tipe_input -->
        <input type="hidden" name="tipe_input" value="satu_input">
        
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target</label>
                    <input type="number" name="target" step="0.01" required min="0"
                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                           value="{{ old('target') }}"
                           placeholder="0.00">
                    @error('target')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Dalam satuan yang sesuai (misal: 100 untuk 100 perkara)</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rumus</label>
                    <input type="text" name="rumus" required
                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                           value="{{ old('rumus') }}"
                           placeholder="Contoh: (Jumlah Diselesaikan / Target) × 100%">
                    @error('rumus')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Input Label untuk Input 1 -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Label Input</label>
                <input type="text" name="label_input_1" required
                       class="w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white"
                       value="{{ old('label_input_1') }}"
                       placeholder="Contoh: Jumlah Perkara Diselesaikan">
                @error('label_input_1')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
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