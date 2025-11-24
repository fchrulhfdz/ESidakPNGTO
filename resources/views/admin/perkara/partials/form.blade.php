<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Sasaran Strategis</label>
        <input type="text" name="sasaran_strategis" required
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Target (%)</label>
        <input type="number" name="target" step="0.01" required
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
</div>

<div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-2">Rumus</label>
    <textarea name="rumus" required rows="2"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Perkara Diselesaikan</label>
        <input type="number" name="input_1" id="input_1" required
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Perkara Tepat Waktu</label>
        <input type="number" name="input_2" id="input_2" required
               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    
    <div class="flex items-end space-x-4">
        <button type="button" id="hitungBtn" 
                class="bg-green-500 text-white px-6 py-2 rounded-md hover:bg-green-600 transition duration-200">
            HITUNG
        </button>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Realisasi</label>
        <input type="text" id="realisasi" readonly
               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
    </div>
    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Capaian</label>
        <input type="text" id="capaian" readonly
               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50">
    </div>
</div>

<div class="flex justify-end">
    <button type="submit" 
            class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition duration-200">
        TAMBAHKAN
    </button>
</div>