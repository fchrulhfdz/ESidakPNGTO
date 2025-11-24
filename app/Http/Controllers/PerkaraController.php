<?php

namespace App\Http\Controllers;

use App\Models\Perdata;
use App\Models\Pidana;
use App\Models\Tipikor;
use App\Models\PHI;
use App\Models\Hukum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PerkaraController extends Controller
{
    // Method untuk masing-masing jenis perkara
    public function showPerdata(Request $request)
    {
        return $this->showPerkaraByType($request, 'perdata');
    }

    public function showPidana(Request $request)
    {
        return $this->showPerkaraByType($request, 'pidana');
    }

    public function showTipikor(Request $request)
    {
        return $this->showPerkaraByType($request, 'tipikor');
    }

    public function showPHI(Request $request)
    {
        return $this->showPerkaraByType($request, 'phi');
    }

    public function showHukum(Request $request)
    {
        return $this->showPerkaraByType($request, 'hukum');
    }

    // Method utama untuk menampilkan data
    private function showPerkaraByType(Request $request, $jenis)
    {
        // Validasi jenis perkara
        $validJenis = ['perdata', 'pidana', 'tipikor', 'phi', 'hukum'];
        if (!in_array($jenis, $validJenis)) {
            abort(404);
        }

        // Cek authorization
        $user = auth()->user();
        if (!$user->isSuperAdmin() && $user->bagian !== $jenis) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Ambil data berdasarkan jenis - tampilkan SEMUA data, filtering dilakukan di frontend
        $model = $this->getModel($jenis);
        $data = $model::orderBy('tahun', 'desc')
                     ->orderBy('bulan', 'desc')
                     ->orderBy('created_at', 'desc')
                     ->get();

        return view("admin.perkara.{$jenis}", compact('data', 'jenis'));
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
            $jenis = $request->input('jenis');

            Log::info('Store Perkara Request Data:', $request->all());

            // Cek authorization sebelum menyimpan
            if (!$user->isSuperAdmin() && $user->bagian !== $jenis) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menyimpan data di bagian ini.')->withInput();
            }

            // Validasi berbeda untuk Super Admin dan Admin Biasa
            $validationRules = [
                'jenis' => 'required|string|in:perdata,pidana,tipikor,phi,hukum',
                'sasaran_strategis' => 'required|string|max:255',
                'indikator_kinerja' => 'required|string|max:255',
                'target' => 'required|numeric|min:0|max:100',
                'rumus' => 'required|string|max:500',
                'bulan' => 'required|numeric|between:1,12',
                'tahun' => 'required|numeric|min:2020|max:'.(date('Y')+5),
            ];

            if (!$user->isSuperAdmin()) {
                $validationRules['input_1'] = 'required|integer|min:0';
                $validationRules['input_2'] = 'required|integer|min:0';
            } else {
                $validationRules['input_1'] = 'nullable|integer|min:0';
                $validationRules['input_2'] = 'nullable|integer|min:0';
            }

            $validated = $request->validate($validationRules);
            Log::info('Validation passed. Data:', $validated);

            // Konversi bulan ke integer (handle jika ada leading zero)
            $bulan = (int) $validated['bulan'];
            $tahun = (int) $validated['tahun'];
            
            // Handle nilai null/empty dan pastikan tipe data numerik
            $input1 = isset($validated['input_1']) ? (int) $validated['input_1'] : 0;
            $input2 = isset($validated['input_2']) ? (int) $validated['input_2'] : 0;
            $target = (float) $validated['target'];

            Log::info("Converted values - Bulan: $bulan, Tahun: $tahun, Input1: $input1, Input2: $input2, Target: $target");

            // Validasi tambahan untuk kalkulasi
            if ($input1 < 0) $input1 = 0;
            if ($input2 < 0) $input2 = 0;
            if ($input2 > $input1) $input2 = $input1;

            // Kalkulasi realisasi dan capaian dengan handling division by zero
            $realisasi = 0;
            $capaian = 0;

            if ($input1 > 0) {
                $realisasi = ($input2 / $input1) * 100;
            }

            if ($target > 0) {
                $capaian = ($realisasi / $target) * 100;
            }

            // Format nilai ke 2 decimal places
            $realisasi = round($realisasi, 2);
            $capaian = round($capaian, 2);

            Log::info("Calculation complete - Realisasi: $realisasi, Capaian: $capaian");

            // Simpan ke model yang sesuai
            $modelClass = $this->getModel($jenis);
            Log::info("Using model class: " . $modelClass);
            
            // Cari data berdasarkan sasaran_strategis, bulan, dan tahun
            $existingData = $modelClass::where('sasaran_strategis', $validated['sasaran_strategis'])
                                 ->where('bulan', $bulan)
                                 ->where('tahun', $tahun)
                                 ->first();

            if ($existingData) {
                // UPDATE data yang sudah ada
                Log::info("Existing data found. ID: " . $existingData->id);
                $existingData->update([
                    'indikator_kinerja' => $validated['indikator_kinerja'],
                    'target' => $target,
                    'rumus' => $validated['rumus'],
                    'input_1' => $input1,
                    'input_2' => $input2,
                    'realisasi' => $realisasi,
                    'capaian' => $capaian,
                    'updated_at' => now(),
                ]);
                $data = $existingData;
                $message = 'Data berhasil diupdate!';
                Log::info('Data berhasil diupdate:', $data->toArray());
            } else {
                // BUAT data baru hanya jika belum ada
                Log::info("Creating new data...");
                $data = $modelClass::create([
                    'sasaran_strategis' => $validated['sasaran_strategis'],
                    'indikator_kinerja' => $validated['indikator_kinerja'],
                    'target' => $target,
                    'rumus' => $validated['rumus'],
                    'input_1' => $input1,
                    'input_2' => $input2,
                    'realisasi' => $realisasi,
                    'capaian' => $capaian,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $message = 'Data berhasil disimpan!';
                Log::info('Data baru berhasil dibuat:', $data->toArray());
            }

            DB::commit();
            Log::info('Transaction committed successfully');

            return redirect()->back()->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error: ' . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing data: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
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
                'jenis' => 'required|string|in:perdata,pidana,tipikor,phi,hukum',
                'bulan' => 'required|integer|between:1,12',
                'tahun' => 'required|integer|min:2020',
            ]);

            // Pastikan target bertipe float
            $validated['target'] = (float) $validated['target'];
            $validated['bulan'] = (int) $validated['bulan'];
            $validated['tahun'] = (int) $validated['tahun'];

            // Cari dan update data
            $model = $this->getModel($validated['jenis']);
            $data = $model::findOrFail($id);

            $data->update($validated);

            return redirect()->back()->with('success', 'Data berhasil diupdate!');

        } catch (\Exception $e) {
            Log::error('Error updating data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $jenis = $request->jenis;
            
            if (!$jenis) {
                return redirect()->back()->with('error', 'Jenis data tidak ditemukan!');
            }

            $model = $this->getModel($jenis);
            $data = $model::find($id);

            if ($data) {
                $data->delete();
                return redirect()->back()->with('success', 'Data berhasil dihapus!');
            }

            return redirect()->back()->with('error', 'Data tidak ditemukan!');

        } catch (\Exception $e) {
            Log::error('Error deleting data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Helper method untuk mendapatkan model berdasarkan jenis
    private function getModel($jenis)
    {
        $models = [
            'perdata' => Perdata::class,
            'pidana' => Pidana::class,
            'tipikor' => Tipikor::class,
            'phi' => PHI::class,
            'hukum' => Hukum::class,
        ];

        if (!array_key_exists($jenis, $models)) {
            abort(404, 'Model tidak ditemukan untuk jenis: ' . $jenis);
        }

        return $models[$jenis];
    }
}