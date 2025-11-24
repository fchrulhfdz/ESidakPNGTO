<script>
document.addEventListener('DOMContentLoaded', function() {
    // Element references
    const pilihSasaran = document.getElementById('pilihSasaran');
    const sasaranHidden = document.getElementById('sasaran_hidden');
    const indikatorHidden = document.getElementById('indikator_hidden');
    const targetHidden = document.getElementById('target_hidden');
    const rumusHidden = document.getElementById('rumus_hidden');
    const displayIndikator = document.getElementById('displayIndikator');
    const hitungBtn = document.getElementById('hitungBtn');
    const submitBtn = document.getElementById('submitBtn');
    const realisasiInput = document.getElementById('realisasi');
    const capaianInput = document.getElementById('capaian');
    const input1 = document.getElementById('input_1');
    const input2 = document.getElementById('input_2');

    // Update hidden fields when dropdown changes
    pilihSasaran.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            sasaranHidden.value = selectedOption.getAttribute('data-sasaran');
            indikatorHidden.value = selectedOption.getAttribute('data-indikator');
            targetHidden.value = selectedOption.getAttribute('data-target');
            rumusHidden.value = selectedOption.getAttribute('data-rumus');
            
            // Update display
            displayIndikator.value = selectedOption.getAttribute('data-indikator');
            
            // Enable submit button
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            submitBtn.classList.add('opacity-100', 'cursor-pointer');
        } else {
            // Disable submit button if no selection
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            submitBtn.classList.remove('opacity-100', 'cursor-pointer');
        }
    });

    // Calculate function
    hitungBtn.addEventListener('click', function() {
        const input1Val = parseInt(input1.value) || 0;
        const input2Val = parseInt(input2.value) || 0;
        const targetVal = parseFloat(targetHidden.value) || 0;

        // Validation
        if (input1Val < 0) input1.value = 0;
        if (input2Val < 0) input2.value = 0;
        if (input2Val > input1Val) input2.value = input1Val;

        let realisasi = 0;
        let capaian = 0;

        // Calculate realisasi
        if (input1Val > 0) {
            realisasi = (input2Val / input1Val) * 100;
        }

        // Calculate capaian
        if (targetVal > 0) {
            capaian = (realisasi / targetVal) * 100;
        }

        // Update display
        realisasiInput.value = realisasi.toFixed(2) + '%';
        capaianInput.value = capaian.toFixed(2) + '%';
    });

    // Form submission validation
    document.getElementById('formInputData').addEventListener('submit', function(e) {
        const input1Val = parseInt(input1.value) || 0;
        const input2Val = parseInt(input2.value) || 0;
        
        if (input1Val === 0 && input2Val === 0) {
            e.preventDefault();
            alert('Mohon isi data input perhitungan!');
            return false;
        }
        
        if (!pilihSasaran.value) {
            e.preventDefault();
            alert('Mohon pilih sasaran strategis!');
            return false;
        }
    });

    // Delete confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                this.submit();
            }
        });
    });

    // Edit modal functionality
    const editModal = document.getElementById('editModal');
    const editForm = document.getElementById('editForm');
    const closeModal = document.getElementById('closeModal');

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

            editForm.action = `/perkara/${id}`;
            editModal.classList.remove('hidden');
        });
    });

    closeModal.addEventListener('click', function() {
        editModal.classList.add('hidden');
    });

    // Close modal when clicking outside
    editModal.addEventListener('click', function(e) {
        if (e.target === editModal) {
            editModal.classList.add('hidden');
        }
    });
});
</script>

<style>
.tooltip {
    position: relative;
    display: inline-block;
}

.tooltip .tooltiptext {
    visibility: hidden;
    width: 300px;
    background-color: black;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px;
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    margin-left: -150px;
    opacity: 0;
    transition: opacity 0.3s;
}

.tooltip:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
}
</style>