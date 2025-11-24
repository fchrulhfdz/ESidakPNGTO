<div class="overflow-x-auto">
    <table class="min-w-full table-auto">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sasaran Strategis</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rumus</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Input</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Realisasi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capaian</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($data as $index => $item)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                <td class="px-6 py-4">{{ $item->sasaran_strategis }}</td>
                <td class="px-6 py-4">{{ $item->target }}%</td>
                <td class="px-6 py-4">{{ $item->rumus }}</td>
                <td class="px-6 py-4">
                    <div>Diselesaikan: {{ $item->input_1 }}</div>
                    <div>Tepat Waktu: {{ $item->input_2 }}</div>
                </td>
                <td class="px-6 py-4">{{ $item->realisasi }}%</td>
                <td class="px-6 py-4">{{ $item->capaian }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>