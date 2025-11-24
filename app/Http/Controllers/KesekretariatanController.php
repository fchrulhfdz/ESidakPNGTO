<?php

namespace App\Http\Controllers;

use App\Models\PTIP;
use App\Models\UmumKeuangan;
use App\Models\Kepegawaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KesekretariatanController extends Controller
{
    public function show(Request $request)
    {
        // Dapatkan jenis dari URL path
        $path = $request->path();
        $jenis = $this->getJenisFromPath($path);
        
        // Mapping URL ke model
        $modelMapping = [
            'ptip' => PTIP::class,
            'umum-keuangan' => UmumKeuangan::class,
            'kepegawaian' => Kepegawaian::class,
        ];

        if (!array_key_exists($jenis, $modelMapping)) {
            abort(404);
        }

        // Cek authorization
        $user = auth()->user();
        $dbBagian = $this->getDbBagian($jenis);
        
        if (!$user->isSuperAdmin() && $user->bagian !== $dbBagian) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $model = $modelMapping[$jenis];
        $data = $model::orderBy('created_at', 'desc')->get();

        return view("admin.kesekretariatan.{$jenis}", compact('data', 'jenis'));
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'input_1' => 'required|integer|min:0',
            'input_2' => 'required|integer|min:0',
            'target' => 'required|numeric|min:0'
        ]);

        $input1 = (int) $request->input_1;
        $input2 = (int) $request->input_2;
        $target = (float) $request->target;

        // Handle invalid values
        if ($input1 < 0) $input1 = 0;
        if ($input2 < 0) $input2 = 0;
        if ($input2 > $input1) $input2 = $input1;

        $realisasi = 0;
        $capaian = 0;

        if ($input1 > 0) {
            $realisasi = ($input2 / $input1) * 100;
        }

        if ($target > 0) {
            $capaian = ($realisasi / $target) * 100;
        }

        return response()->json([
            'realisasi' => number_format($realisasi, 2),
            'capaian' => number_format($capaian, 2)
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $user = auth()->user();
            $jenis = $request->jenis;

            // Cek authorization sebelum menyimpan
            if (!$user->isSuperAdmin() && $user->bagian !== $jenis) {
                abort(403, 'Anda tidak memiliki akses untuk menyimpan data di bagian ini.');
            }

            // Validasi berbeda untuk Super Admin dan Admin Biasa
            if ($user->isSuperAdmin()) {
                $validated = $request->validate([
                    'jenis' => 'required|string|in:ptip,umum_keuangan,kepegawaian',
                    'sasaran_strategis' => 'required|string|max:255',
                    'indikator_kinerja' => 'required|string|max:255',
                    'target' => 'required|numeric|min:0|max:100',
                    'rumus' => 'required|string|max:500',
                    'input_1' => 'nullable|integer|min:0',
                    'input_2' => 'nullable|integer|min:0',
                ]);
            } else {
                $validated = $request->validate([
                    'jenis' => 'required|string|in:ptip,umum_keuangan,kepegawaian',
                    'sasaran_strategis' => 'required|string|max:255',
                    'indikator_kinerja' => 'required|string|max:255',
                    'target' => 'required|numeric|min:0|max:100',
                    'rumus' => 'required|string|max:500',
                    'input_1' => 'required|integer|min:0',
                    'input_2' => 'required|integer|min:0',
                ]);
            }

            // Handle nilai null/empty dan pastikan tipe data numerik
            $input1 = isset($validated['input_1']) ? (int) $validated['input_1'] : 0;
            $input2 = isset($validated['input_2']) ? (int) $validated['input_2'] : 0;
            $target = (float) $validated['target'];

            // Kalkulasi realisasi dan capaian
            $realisasi = 0;
            $capaian = 0;

            if ($input1 > 0) {
                $realisasi = ($input2 / $input1) * 100;
            }

            if ($target > 0) {
                $capaian = ($realisasi / $target) * 100;
            }

            // Format nilai
            $realisasi = round($realisasi, 2);
            $capaian = round($capaian, 2);

            // Simpan ke model yang sesuai
            $modelMapping = [
                'ptip' => PTIP::class,
                'umum_keuangan' => UmumKeuangan::class,
                'kepegawaian' => Kepegawaian::class,
            ];

            $model = $modelMapping[$jenis];
            $data = $model::create([
                'sasaran_strategis' => $validated['sasaran_strategis'],
                'indikator_kinerja' => $validated['indikator_kinerja'],
                'target' => $target,
                'rumus' => $validated['rumus'],
                'input_1' => $input1,
                'input_2' => $input2,
                'realisasi' => $realisasi,
                'capaian' => $capaian,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Data berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validasi
            $validated = $request->validate([
                'sasaran_strategis' => 'required|string|max:255',
                'indikator_kinerja' => 'required|string|max:255',
                'target' => 'required|numeric|min:0|max:100',
                'rumus' => 'required|string|max:500',
                'jenis' => 'required|string|in:ptip,umum_keuangan,kepegawaian',
            ]);

            // Pastikan target bertipe float
            $validated['target'] = (float) $validated['target'];

            // Cari dan update data
            $modelMapping = [
                'ptip' => PTIP::class,
                'umum_keuangan' => UmumKeuangan::class,
                'kepegawaian' => Kepegawaian::class,
            ];

            $model = $modelMapping[$validated['jenis']];
            $data = $model::findOrFail($id);

            $data->update($validated);

            return redirect()->back()->with('success', 'Data berhasil diupdate!');

        } catch (\Exception $e) {
            Log::error('Error updating data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Cari data di semua model kesekretariatan
            $models = [
                'ptip' => PTIP::class,
                'umum_keuangan' => UmumKeuangan::class,
                'kepegawaian' => Kepegawaian::class,
            ];

            foreach ($models as $modelClass) {
                $data = $modelClass::find($id);
                if ($data) {
                    $data->delete();
                    return redirect()->back()->with('success', 'Data berhasil dihapus!');
                }
            }

            return redirect()->back()->with('error', 'Data tidak ditemukan!');

        } catch (\Exception $e) {
            Log::error('Error deleting data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Helper untuk mapping URL ke nilai database
    private function getDbBagian($urlBagian)
    {
        $mapping = [
            'ptip' => 'ptip',
            'umum-keuangan' => 'umum_keuangan',
            'kepegawaian' => 'kepegawaian'
        ];

        return $mapping[$urlBagian] ?? $urlBagian;
    }

    // Helper method untuk mendapatkan jenis dari path URL
    private function getJenisFromPath($path)
    {
        $path = trim($path, '/');
        $segments = explode('/', $path);
        return $segments[0] ?? null;
    }
}