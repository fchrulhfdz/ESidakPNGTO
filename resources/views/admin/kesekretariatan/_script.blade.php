<!-- Modal Edit (Untuk Super Admin) -->
@if(auth()->user()->isSuperAdmin())
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Edit Data</h3>
            <form id="formEditData" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="jenis" id="edit_jenis">
                <input type="hidden" id="edit_id" name="id">
                
                <div class="grid grid-cols-1 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sasaran Strategis</label>
                        <input type="text" id="edit_sasaran" name="sasaran_strategis" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Indikator Kinerja</label>
                        <input type="text" id="edit_indikator" name="indikator_kinerja" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target (%)</label>
                        <input type="number" id="edit_target" name="target" step="0.01" required min="0" max="100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rumus</label>
                        <input type="text" id="edit_rumus" name="rumus" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="batalEditBtn" 
                            class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition duration-200">
                        Batal
                    </button>
                    <button type="submit" 
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
// Pilih Sasaran Strategis
document.getElementById('pilihSasaran').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const indikatorSelect = document.getElementById('pilihIndikator');
    const submitBtn = document.getElementById('submitBtn');
    
    if (selectedOption.value) {
        // Isi hidden fields
        document.getElementById('sasaran_hidden').value = selectedOption.getAttribute('data-sasaran');
        document.getElementById('indikator_hidden').value = selectedOption.getAttribute('data-indikator');
        document.getElementById('target_hidden').value = selectedOption.getAttribute('data-target');
        document.getElementById('rumus_hidden').value = selectedOption.getAttribute('data-rumus');
        
        // Update dropdown indikator
        indikatorSelect.innerHTML = `<option value="${selectedOption.getAttribute('data-indikator')}">${selectedOption.getAttribute('data-indikator')}</option>`;
        indikatorSelect.disabled = false;
        indikatorSelect.classList.remove('bg-gray-100');
        
        // Aktifkan tombol simpan
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    } else {
        // Reset
        indikatorSelect.innerHTML = '<option value="">Pilih Sasaran Strategis terlebih dahulu</option>';
        indikatorSelect.disabled = true;
        indikatorSelect.classList.add('bg-gray-100');
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        
        // Reset hidden fields
        document.getElementById('sasaran_hidden').value = '';
        document.getElementById('indikator_hidden').value = '';
        document.getElementById('target_hidden').value = '';
        document.getElementById('rumus_hidden').value = '';
    }
});

// Kalkulasi
document.getElementById('hitungBtn').addEventListener('click', function() {
    const input1 = parseFloat(document.getElementById('input_1').value) || 0;
    const input2 = parseFloat(document.getElementById('input_2').value) || 0;
    const target = parseFloat(document.getElementById('target_hidden').value) || 0;

    // Validasi
    if (!document.getElementById('pilihSasaran').value) {
        alert('Pilih sasaran strategis terlebih dahulu!');
        return;
    }

    if (isNaN(input1) || isNaN(input2)) {
        alert('Harap isi jumlah kegiatan diselesaikan dan tepat waktu!');
        return;
    }

    if (input1 <= 0) {
        alert('Jumlah kegiatan diselesaikan harus lebih dari 0!');
        return;
    }

    if (input2 > input1) {
        alert('Jumlah kegiatan tepat waktu tidak boleh lebih besar dari jumlah yang diselesaikan!');
        return;
    }

    // Kalkulasi
    const realisasi = (input2 / input1) * 100;
    const capaian = target > 0 ? (realisasi / target) * 100 : 0;

    // Format output
    document.getElementById('realisasi').value = realisasi.toFixed(2) + '%';
    document.getElementById('capaian').value = capaian.toFixed(2) + '%';
});

// Auto-kalkulasi saat input berubah
document.getElementById('input_1').addEventListener('input', function() {
    if (this.value && document.getElementById('input_2').value && document.getElementById('pilihSasaran').value) {
        document.getElementById('hitungBtn').click();
    }
});

document.getElementById('input_2').addEventListener('input', function() {
    if (this.value && document.getElementById('input_1').value && document.getElementById('pilihSasaran').value) {
        document.getElementById('hitungBtn').click();
    }
});

// Modal Edit (Super Admin)
@if(auth()->user()->isSuperAdmin())
document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const sasaran = this.getAttribute('data-sasaran');
        const indikator = this.getAttribute('data-indikator');
        const target = this.getAttribute('data-target');
        const rumus = this.getAttribute('data-rumus');
        const jenis = this.getAttribute('data-jenis');
        
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_sasaran').value = sasaran;
        document.getElementById('edit_indikator').value = indikator;
        document.getElementById('edit_target').value = target;
        document.getElementById('edit_rumus').value = rumus;
        document.getElementById('edit_jenis').value = jenis;
        
        document.getElementById('formEditData').action = `{{ url('/kesekretariatan') }}/${id}`;
        document.getElementById('editModal').classList.remove('hidden');
    });
});

document.getElementById('batalEditBtn').addEventListener('click', function() {
    document.getElementById('editModal').classList.add('hidden');
});

// Tutup modal ketika klik di luar
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target.id === 'editModal') {
        document.getElementById('editModal').classList.add('hidden');
    }
});
@endif

// Tooltip untuk rumus
document.querySelectorAll('.tooltip').forEach(tooltip => {
    tooltip.addEventListener('mouseenter', function() {
        this.querySelector('.tooltiptext').classList.remove('hidden');
    });
    tooltip.addEventListener('mouseleave', function() {
        this.querySelector('.tooltiptext').classList.add('hidden');
    });
});

// Reset form setelah submit berhasil
@if(session('success'))
    setTimeout(() => {
        document.getElementById('input_1').value = '';
        document.getElementById('input_2').value = '';
        document.getElementById('realisasi').value = '';
        document.getElementById('capaian').value = '';
        document.getElementById('pilihSasaran').value = '';
        document.getElementById('pilihIndikator').innerHTML = '<option value="">Pilih Sasaran Strategis terlebih dahulu</option>';
        document.getElementById('pilihIndikator').disabled = true;
        document.getElementById('pilihIndikator').classList.add('bg-gray-100');
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitBtn').classList.add('opacity-50', 'cursor-not-allowed');
        
        // Reset hidden fields
        document.getElementById('sasaran_hidden').value = '';
        document.getElementById('indikator_hidden').value = '';
        document.getElementById('target_hidden').value = '';
        document.getElementById('rumus_hidden').value = '';
    }, 1000);
@endif
</script>

<style>
.tooltip {
    position: relative;
    display: inline-block;
}

.tooltiptext {
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    transform: translateX(-50%);
    white-space: normal;
    word-wrap: break-word;
}

.tooltiptext::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #1F2937 transparent transparent transparent;
}
</style>