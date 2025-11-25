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
        $validJenis = ['perdata', 'pidana', 'tipikor', 'phi', 'hukum'];
        if (!in_array($jenis, $validJenis)) {
            abort(404);
        }

        $user = auth()->user();
        if (!$user->isSuperAdmin() && $user->role !== $jenis) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

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

            // Cek authorization
            if (!$user->isSuperAdmin() && $user->role !== $jenis) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menyimpan data di bagian ini.')->withInput();
            }

            // Validasi berbeda untuk Super Admin dan Admin Biasa
            $validationRules = [
                'jenis' => 'required|string|in:perdata,pidana,tipikor,phi,hukum',
                'bulan' => 'required|numeric|between:1,12',
                'tahun' => 'required|numeric|min:2020|max:'.(date('Y')+5),
            ];

            // Jika user adalah Super Admin (menambah sasaran strategis baru)
            if ($user->isSuperAdmin() && $request->has('sasaran_strategis')) {
                $validationRules['sasaran_strategis'] = 'required|string|max:255';
                $validationRules['indikator_kinerja'] = 'required|string|max:255';
                $validationRules['target'] = 'required|numeric|min:0|max:100';
                $validationRules['rumus'] = 'required|string|max:500';
                $validationRules['label_input_1'] = 'required|string|max:255';
                $validationRules['label_input_2'] = 'required|string|max:255';
                $validationRules['input_1'] = 'nullable|integer|min:0';
                $validationRules['input_2'] = 'nullable|integer|min:0';
            } else {
                // Untuk Admin Biasa atau Super Admin yang input data
                $validationRules['sasaran_strategis'] = 'required|string|max:255';
                $validationRules['indikator_kinerja'] = 'required|string|max:255';
                $validationRules['input_1'] = 'required|integer|min:0';
                $validationRules['input_2'] = 'required|integer|min:0';
                // Untuk non-Super Admin, kita akan mengambil target dan rumus dari database
            }

            $validated = $request->validate($validationRules);
            Log::info('Validation passed. Data:', $validated);

            // Konversi data
            $bulan = (int) $validated['bulan'];
            $tahun = (int) $validated['tahun'];
            
            $input1 = isset($validated['input_1']) ? (int) $validated['input_1'] : 0;
            $input2 = isset($validated['input_2']) ? (int) $validated['input_2'] : 0;

            // Untuk Super Admin yang membuat sasaran baru
            if ($user->isSuperAdmin() && $request->has('sasaran_strategis')) {
                $target = (float) $validated['target'];
                $labelInput1 = $validated['label_input_1'];
                $labelInput2 = $validated['label_input_2'];
                $rumus = $validated['rumus'];
            } else {
                // Untuk Admin Biasa atau input data, ambil target dari database berdasarkan sasaran yang dipilih
                $modelClass = $this->getModel($jenis);
                $existingSasaran = $modelClass::where('sasaran_strategis', $validated['sasaran_strategis'])
                    ->where('indikator_kinerja', $validated['indikator_kinerja'])
                    ->first();
                
                if ($existingSasaran) {
                    $target = (float) $existingSasaran->target;
                    $rumus = $existingSasaran->rumus;
                    $labelInput1 = $existingSasaran->label_input_1;
                    $labelInput2 = $existingSasaran->label_input_2;
                } else {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Sasaran strategis tidak ditemukan.')->withInput();
                }
            }

            Log::info("Converted values - Bulan: $bulan, Tahun: $tahun, Input1: $input1, Input2: $input2, Target: $target, Label1: $labelInput1, Label2: $labelInput2");

            // Validasi tambahan
            if ($input1 < 0) $input1 = 0;
            if ($input2 < 0) $input2 = 0;
            if ($input2 > $input1) $input2 = $input1;

            // Kalkulasi
            $realisasi = 0;
            $capaian = 0;

            if ($input1 > 0) {
                $realisasi = ($input2 / $input1) * 100;
            }

            if ($target > 0) {
                $capaian = ($realisasi / $target) * 100;
            }

            $realisasi = round($realisasi, 2);
            $capaian = round($capaian, 2);

            Log::info("Calculation complete - Realisasi: $realisasi, Capaian: $capaian");

            // Simpan ke model
            $modelClass = $this->getModel($jenis);
            Log::info("Using model class: " . $modelClass);
            
            // Cari data yang sudah ada berdasarkan sasaran_strategis, indikator_kinerja, bulan, dan tahun
            $existingData = $modelClass::where('sasaran_strategis', $validated['sasaran_strategis'])
                                 ->where('indikator_kinerja', $validated['indikator_kinerja'])
                                 ->where('bulan', $bulan)
                                 ->where('tahun', $tahun)
                                 ->first();

            if ($existingData) {
                // UPDATE data yang sudah ada
                Log::info("Existing data found. ID: " . $existingData->id);
                $updateData = [
                    'input_1' => $input1,
                    'input_2' => $input2,
                    'realisasi' => $realisasi,
                    'capaian' => $capaian,
                    'updated_at' => now(),
                ];

                // Hanya Super Admin yang bisa update label dan target saat membuat sasaran baru
                if ($user->isSuperAdmin() && $request->has('sasaran_strategis')) {
                    $updateData['target'] = $target;
                    $updateData['rumus'] = $rumus;
                    $updateData['label_input_1'] = $labelInput1;
                    $updateData['label_input_2'] = $labelInput2;
                }

                $existingData->update($updateData);
                $data = $existingData;
                $message = 'Data berhasil diupdate!';
                Log::info('Data berhasil diupdate:', $data->toArray());
            } else {
                // BUAT data baru
                Log::info("Creating new data...");
                $createData = [
                    'sasaran_strategis' => $validated['sasaran_strategis'],
                    'indikator_kinerja' => $validated['indikator_kinerja'],
                    'input_1' => $input1,
                    'input_2' => $input2,
                    'realisasi' => $realisasi,
                    'capaian' => $capaian,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Hanya Super Admin yang bisa set label dan target saat membuat sasaran baru
                if ($user->isSuperAdmin() && $request->has('sasaran_strategis')) {
                    $createData['target'] = $target;
                    $createData['rumus'] = $rumus;
                    $createData['label_input_1'] = $labelInput1;
                    $createData['label_input_2'] = $labelInput2;
                } else {
                    // Untuk non-Super Admin, gunakan target dan rumus dari sasaran yang dipilih
                    $createData['target'] = $target;
                    $createData['rumus'] = $rumus;
                    $createData['label_input_1'] = $labelInput1;
                    $createData['label_input_2'] = $labelInput2;
                }

                $data = $modelClass::create($createData);
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
            $user = auth()->user();
            
            $validationRules = [
                'sasaran_strategis' => 'required|string|max:255',
                'indikator_kinerja' => 'required|string|max:255',
                'target' => 'required|numeric|min:0|max:100',
                'rumus' => 'required|string|max:500',
                'jenis' => 'required|string|in:perdata,pidana,tipikor,phi,hukum',
                'bulan' => 'required|integer|between:1,12',
                'tahun' => 'required|integer|min:2020',
            ];

            // Hanya Super Admin yang bisa mengupdate label
            if ($user->isSuperAdmin()) {
                $validationRules['label_input_1'] = 'required|string|max:255';
                $validationRules['label_input_2'] = 'required|string|max:255';
            }

            $validated = $request->validate($validationRules);

            $validated['target'] = (float) $validated['target'];
            $validated['bulan'] = (int) $validated['bulan'];
            $validated['tahun'] = (int) $validated['tahun'];

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