<!-- Tab Navigation -->
<div class="mb-8">
    <div class="flex space-x-1 bg-gray-100 p-1 rounded-xl w-fit">
        <button id="dataTab" class="tab-button active py-2 px-4 rounded-lg font-medium text-sm transition-all duration-200 bg-white text-gray-900 shadow-sm">
            Data Perkara
        </button>
         @if(auth()->check() && auth()->user()->role !== 'read_only')
            <button id="inputData1Tab" class="tab-button py-2 px-4 rounded-lg font-medium text-sm transition-all duration-200 text-gray-600 hover:text-gray-900">
                Input Data (Dua Input)
            </button>
            <button id="inputData2Tab" class="tab-button py-2 px-4 rounded-lg font-medium text-sm transition-all duration-200 text-gray-600 hover:text-gray-900">
                Input Data (Satu Input)
            </button>
            @if(auth()->user()->isSuperAdmin())
            <button id="sasaranTab2Input" class="tab-button py-2 px-4 rounded-lg font-medium text-sm transition-all duration-200 text-gray-600 hover:text-gray-900">
                Sasaran Strategis (2 Input)
            </button>
            <button id="sasaranTab1Input" class="tab-button py-2 px-4 rounded-lg font-medium text-sm transition-all duration-200 text-gray-600 hover:text-gray-900">
                Sasaran Strategis (1 Input)
            </button>
            @endif
        @endif
        <button id="lampiranTab" class="tab-button py-2 px-4 rounded-lg font-medium text-sm transition-all duration-200 text-gray-600 hover:text-gray-900">
            Lampiran
        </button>
    </div>
</div>