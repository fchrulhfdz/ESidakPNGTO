<?php

namespace App\Http\Controllers;

use App\Models\Perdata;
use App\Models\Pidana;
use App\Models\Tipikor;
use App\Models\Phi;
use App\Models\Hukum;
use App\Models\PerdataLampiran;
use App\Models\PidanaLampiran;
use App\Models\TipikorLampiran;
use App\Models\PhiLampiran;
use App\Models\HukumLampiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PerkaraController extends Controller
{
    // ==================== METHOD KHUSUS LAMPIRAN PERDATA ====================
    public function showLampiranPerdata(Request $request)
    {
        return $this->showLampiran($request, 'perdata');
    }

    public function storeLampiranPerdata(Request $request)
    {
        return $this->storeLampiran($request, 'perdata');
    }

    public function updateLampiranPerdata(Request $request, $id)
    {
        return $this->updateLampiran($request, 'perdata', $id);
    }

    public function destroyLampiranPerdata($id)
    {
        return $this->destroyLampiran('perdata', $id);
    }

    public function downloadLampiranPerdata($id)
    {
        return $this->downloadLampiran('perdata', $id);
    }

    // ==================== METHOD KHUSUS LAMPIRAN PIDANA ====================
    public function showLampiranPidana(Request $request)
    {
        return $this->showLampiran($request, 'pidana');
    }

    public function storeLampiranPidana(Request $request)
    {
        return $this->storeLampiran($request, 'pidana');
    }

    public function updateLampiranPidana(Request $request, $id)
    {
        return $this->updateLampiran($request, 'pidana', $id);
    }

    public function destroyLampiranPidana($id)
    {
        return $this->destroyLampiran('pidana', $id);
    }

    public function downloadLampiranPidana($id)
    {
        return $this->downloadLampiran('pidana', $id);
    }

    // ==================== METHOD KHUSUS LAMPIRAN TIPIKOR ====================
    public function showLampiranTipikor(Request $request)
    {
        return $this->showLampiran($request, 'tipikor');
    }

    public function storeLampiranTipikor(Request $request)
    {
        return $this->storeLampiran($request, 'tipikor');
    }

    public function updateLampiranTipikor(Request $request, $id)
    {
        return $this->updateLampiran($request, 'tipikor', $id);
    }

    public function destroyLampiranTipikor($id)
    {
        return $this->destroyLampiran('tipikor', $id);
    }

    public function downloadLampiranTipikor($id)
    {
        return $this->downloadLampiran('tipikor', $id);
    }

    // ==================== METHOD KHUSUS LAMPIRAN PHI ====================
    public function showLampiranPhi(Request $request)
    {
        return $this->showLampiran($request, 'phi');
    }

    public function storeLampiranPhi(Request $request)
    {
        return $this->storeLampiran($request, 'phi');
    }

    public function updateLampiranPhi(Request $request, $id)
    {
        return $this->updateLampiran($request, 'phi', $id);
    }

    public function destroyLampiranPhi($id)
    {
        return $this->destroyLampiran('phi', $id);
    }

    public function downloadLampiranPhi($id)
    {
        return $this->downloadLampiran('phi', $id);
    }

    // ==================== METHOD KHUSUS LAMPIRAN HUKUM ====================
    public function showLampiranHukum(Request $request)
    {
        return $this->showLampiran($request, 'hukum');
    }

    public function storeLampiranHukum(Request $request)
    {
        return $this->storeLampiran($request, 'hukum');
    }

    public function updateLampiranHukum(Request $request, $id)
    {
        return $this->updateLampiran($request, 'hukum', $id);
    }

    public function destroyLampiranHukum($id)
    {
        return $this->destroyLampiran('hukum', $id);
    }

    public function downloadLampiranHukum($id)
    {
        return $this->downloadLampiran('hukum', $id);
    }

    // ==================== METHOD UTAMA ====================
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

    public function showPhi(Request $request)
    {
        return $this->showPerkaraByType($request, 'phi');
    }

    public function showHukum(Request $request)
    {
        return $this->showPerkaraByType($request, 'hukum');
    }

    private function showPerkaraByType(Request $request, $jenis)
    {
        $validJenis = ['perdata', 'pidana', 'tipikor', 'phi', 'hukum', 'read_only'];
        if (!in_array($jenis, $validJenis)) {
            abort(404);
        }

        $user = auth()->user();

        $allowedRoles = ['super_admin', 'admin', $jenis, 'read_only'];
    
        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $model = $this->getModel($jenis);
        $data = $model::orderBy('tahun', 'desc')
                     ->orderBy('bulan', 'desc')
                     ->orderBy('created_at', 'desc')
                     ->get();

        // Filter sasaran strategis berdasarkan tipe_input untuk dropdown
        $sasaran_strategis_dua_input = $model::where('input_1', '=', 0)
            ->where('input_2', '=', 0)
            ->where('tipe_input', 'dua_input')
            ->distinct('sasaran_strategis')
            ->get();

        $sasaran_strategis_satu_input = $model::where('input_1', '=', 0)
            ->where('input_2', '=', 0)
            ->where('tipe_input', 'satu_input')
            ->distinct('sasaran_strategis')
            ->get();

        // Kelompokkan data untuk tampilan tabel berdasarkan tipe_input
        $data_dua_input = $data->where('tipe_input', 'dua_input');
        $data_satu_input = $data->where('tipe_input', 'satu_input');

        return view("admin.{$jenis}.index", compact('data', 'jenis', 'sasaran_strategis_dua_input', 'sasaran_strategis_satu_input', 'data_dua_input', 'data_satu_input'));
    }

    // API untuk mendapatkan sasaran strategis berdasarkan tipe_input
    public function getSasaranStrategisByTipe(Request $request)
    {
        $request->validate([
            'jenis' => 'required|string|in:perdata,pidana,tipikor,phi,hukum',
            'tipe_input' => 'required|string|in:dua_input,satu_input'
        ]);

        $model = $this->getModel($request->jenis);
        
        $data = $model::where('input_1', 0)
                     ->where('input_2', 0)
                     ->where('tipe_input', $request->tipe_input)
                     ->distinct('sasaran_strategis')
                     ->get();

        return response()->json($data);
    }

    public function calculateDuaInput(Request $request)
    {
        $request->validate([
            'input_1' => 'required|integer|min:0',
            'input_2' => 'required|integer|min:0',
            'target' => 'required|numeric|min:0'
        ]);

        $input1 = (int) $request->input_1;
        $input2 = (int) $request->input_2;
        $target = (float) $request->target;

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

        $status_capaian = $this->tentukanStatusCapaian($capaian);

        return response()->json([
            'realisasi' => number_format($realisasi, 2),
            'capaian' => number_format($capaian, 2),
            'status_capaian' => $status_capaian,
            'persentase' => number_format($capaian, 2) . '%',
            'progress_width' => min($capaian, 100)
        ]);
    }

    public function calculateSatuInput(Request $request)
    {
        $request->validate([
            'input_1' => 'required|numeric|min:0',
            'target' => 'required|numeric|min:0'
        ]);

        $input1 = (float) $request->input_1;
        $target = (float) $request->target;

        if ($target == 0) {
            return response()->json([
                'capaian' => 0,
                'status_capaian' => 'Belum Tercapai',
                'persentase' => '0%',
                'progress_width' => 0
            ]);
        }

        $capaian = ($input1 / $target) * 100;
        
        $status_capaian = $this->tentukanStatusCapaian($capaian);

        return response()->json([
            'capaian' => number_format($capaian, 2),
            'status_capaian' => $status_capaian,
            'persentase' => number_format($capaian, 2) . '%',
            'progress_width' => min($capaian, 100)
        ]);
    }

    private function tentukanStatusCapaian($capaian)
    {
        if ($capaian >= 100) {
            return 'Tercapai';
        } elseif ($capaian >= 80) {
            return 'Hampir Tercapai';
        } else {
            return 'Belum Tercapai';
        }
    }

    public function getIndikatorKinerja(Request $request)
    {
        $request->validate([
            'jenis' => 'required|string|in:perdata,pidana,tipikor,phi,hukum',
            'sasaran_strategis' => 'required|string|max:500',
            'tipe_input' => 'required|string|in:dua_input,satu_input'
        ]);

        $model = $this->getModel($request->jenis);
        $data = $model::where('sasaran_strategis', $request->sasaran_strategis)
                     ->where('tipe_input', $request->tipe_input)
                     ->where('input_1', 0)
                     ->where('input_2', 0)
                     ->distinct('indikator_kinerja')
                     ->get();

        return response()->json($data);
    }

    public function getSasaranDetail(Request $request)
    {
        $request->validate([
            'jenis' => 'required|string|in:perdata,pidana,tipikor,phi,hukum',
            'sasaran_strategis' => 'required|string|max:500',
            'indikator_kinerja' => 'required|string|max:500',
            'tipe_input' => 'required|string|in:dua_input,satu_input'
        ]);

        $model = $this->getModel($request->jenis);
        $data = $model::where('sasaran_strategis', $request->sasaran_strategis)
                     ->where('indikator_kinerja', $request->indikator_kinerja)
                     ->where('tipe_input', $request->tipe_input)
                     ->where('input_1', 0)
                     ->where('input_2', 0)
                     ->first();

        if (!$data) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'target' => $data->target,
            'rumus' => $data->rumus,
            'label_input_1' => $data->label_input_1,
            'label_input_2' => $data->label_input_2,
            'tipe_input' => $data->tipe_input
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $user = auth()->user();
            $jenis = $request->input('jenis');
            $isSasaranBaru = $user->isSuperAdmin() && $request->has('sasaran_strategis') && $request->filled('sasaran_strategis');
            $tipe_input = $request->input('tipe_input', 'dua_input');

            Log::info('Store Perkara Request Data:', $request->all());

            if (!$user->isSuperAdmin() && $user->role !== $jenis) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menyimpan data di bagian ini.')->withInput();
            }

            $validationRules = [
                'jenis' => 'required|string|in:perdata,pidana,tipikor,phi,hukum',
                'bulan' => 'required|numeric|between:1,12',
                'tahun' => 'required|numeric|min:2020|max:' . (date('Y') + 5),
                'tipe_input' => 'required|in:dua_input,satu_input',
            ];

            if ($isSasaranBaru) {
                $validationRules['sasaran_strategis'] = 'required|string|max:500';
                $validationRules['indikator_kinerja'] = 'required|string|max:500';
                $validationRules['target'] = 'required|numeric|min:0';
                
                if ($tipe_input == 'dua_input') {
                    $validationRules['target'] = 'required|numeric|min:0|max:100';
                }
                
                $validationRules['rumus'] = 'required|string|max:500';
                $validationRules['label_input_1'] = 'required|string|max:100';
                
                if ($tipe_input == 'dua_input') {
                    $validationRules['label_input_2'] = 'required|string|max:100';
                    $validationRules['input_2'] = 'nullable|integer|min:0';
                } else {
                    $validationRules['label_input_2'] = 'nullable|string|max:100';
                    $validationRules['input_2'] = 'nullable|integer|min:0';
                }
                
                $validationRules['input_1'] = 'nullable|integer|min:0';

                $validationRules['hambatan'] = 'nullable|string';
                $validationRules['rekomendasi'] = 'nullable|string';
                $validationRules['tindak_lanjut'] = 'nullable|string';
                $validationRules['keberhasilan'] = 'nullable|string';
            } else {
                $validationRules['sasaran_strategis'] = 'required|string|max:500';
                $validationRules['indikator_kinerja'] = 'required|string|max:500';
                $validationRules['input_1'] = 'required|integer|min:0';
                
                if ($tipe_input == 'dua_input') {
                    $validationRules['input_2'] = 'required|integer|min:0';
                }
                
                $validationRules['hambatan'] = 'nullable|string';
                $validationRules['rekomendasi'] = 'nullable|string';
                $validationRules['tindak_lanjut'] = 'nullable|string';
                $validationRules['keberhasilan'] = 'nullable|string';
            }

            $validated = $request->validate($validationRules);
            Log::info('Validation passed. Data:', $validated);

            $bulan = (int) $validated['bulan'];
            $tahun = (int) $validated['tahun'];
            
            $input1 = isset($validated['input_1']) ? (int) $validated['input_1'] : 0;
            $input2 = isset($validated['input_2']) ? (int) $validated['input_2'] : 0;

            if ($isSasaranBaru) {
                $target = (float) $validated['target'];
                $labelInput1 = $validated['label_input_1'];
                $labelInput2 = $validated['label_input_2'] ?? null;
                $rumus = $validated['rumus'];
            } else {
                $modelClass = $this->getModel($jenis);
                $existingSasaran = $modelClass::where('sasaran_strategis', $validated['sasaran_strategis'])
                    ->where('indikator_kinerja', $validated['indikator_kinerja'])
                    ->where('tipe_input', $tipe_input)
                    ->first();
                
                if ($existingSasaran) {
                    $target = (float) $existingSasaran->target;
                    $rumus = $existingSasaran->rumus;
                    $labelInput1 = $existingSasaran->label_input_1;
                    $labelInput2 = $existingSasaran->label_input_2;
                    $tipe_input = $existingSasaran->tipe_input;
                } else {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Sasaran strategis tidak ditemukan atau tipe input tidak sesuai.')->withInput();
                }
            }

            Log::info("Converted values - Bulan: $bulan, Tahun: $tahun, Input1: $input1, Input2: $input2, Target: $target, Tipe: $tipe_input");

            if ($input1 < 0) $input1 = 0;
            if ($input2 < 0) $input2 = 0;
            if ($tipe_input == 'dua_input' && $input2 > $input1) $input2 = $input1;

            $realisasi = 0;
            $capaian = 0;

            if ($tipe_input == 'dua_input') {
                if ($input1 > 0) {
                    $realisasi = ($input2 / $input1) * 100;
                }

                if ($target > 0) {
                    $capaian = ($realisasi / $target) * 100;
                }
            } else {
                if ($target > 0) {
                    $capaian = ($input1 / $target) * 100;
                }
                $realisasi = null;
            }

            $realisasi = $realisasi !== null ? round($realisasi, 2) : null;
            $capaian = round($capaian, 2);

            $status_capaian = $this->tentukanStatusCapaian($capaian);

            Log::info("Calculation complete - Realisasi: $realisasi, Capaian: $capaian, Status: $status_capaian");

            $modelClass = $this->getModel($jenis);
            Log::info("Using model class: " . $modelClass);
            
            $existingData = $modelClass::where('sasaran_strategis', $validated['sasaran_strategis'])
                                 ->where('indikator_kinerja', $validated['indikator_kinerja'])
                                 ->where('tipe_input', $tipe_input)
                                 ->where('bulan', $bulan)
                                 ->where('tahun', $tahun)
                                 ->first();

            $analisisData = [
                'hambatan' => $validated['hambatan'] ?? null,
                'rekomendasi' => $validated['rekomendasi'] ?? null,
                'tindak_lanjut' => $validated['tindak_lanjut'] ?? null,
                'keberhasilan' => $validated['keberhasilan'] ?? null,
            ];

            if ($existingData) {
                Log::info("Existing data found. ID: " . $existingData->id);
                $updateData = [
                    'input_1' => $input1,
                    'input_2' => $tipe_input == 'dua_input' ? $input2 : null,
                    'realisasi' => $realisasi,
                    'capaian' => $capaian,
                    'status_capaian' => $status_capaian,
                    'tipe_input' => $tipe_input,
                    'updated_at' => now(),
                ];

                $updateData = array_merge($updateData, $analisisData);

                if ($isSasaranBaru) {
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
                Log::info("Creating new data...");
                $createData = [
                    'sasaran_strategis' => $validated['sasaran_strategis'],
                    'indikator_kinerja' => $validated['indikator_kinerja'],
                    'input_1' => $input1,
                    'input_2' => $tipe_input == 'dua_input' ? $input2 : null,
                    'realisasi' => $realisasi,
                    'capaian' => $capaian,
                    'status_capaian' => $status_capaian,
                    'tipe_input' => $tipe_input,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                ];

                $createData = array_merge($createData, $analisisData);

                if ($isSasaranBaru) {
                    $createData['target'] = $target;
                    $createData['rumus'] = $rumus;
                    $createData['label_input_1'] = $labelInput1;
                    $createData['label_input_2'] = $labelInput2;
                } else {
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
        DB::beginTransaction();

        try {
            $user = auth()->user();

            $validationRules = [
                'jenis' => 'required|string|in:perdata,pidana,tipikor,phi,hukum',
                'tipe_input' => 'required|in:dua_input,satu_input',
            ];

            if ($user->isSuperAdmin()) {
                $validationRules = array_merge($validationRules, [
                    'sasaran_strategis' => 'required|string|max:500',
                    'indikator_kinerja' => 'required|string|max:500',
                    'target' => 'required|numeric|min:0',
                    'rumus' => 'required|string|max:500',
                    'label_input_1' => 'required|string|max:100',
                    'label_input_2' => 'nullable|string|max:100',
                    'bulan' => 'required|integer|between:1,12',
                    'tahun' => 'required|integer|min:2020',
                ]);
            }

            $validationRules = array_merge($validationRules, [
                'input_1' => 'required|integer|min:0',
            ]);

            if ($request->tipe_input == 'dua_input') {
                $validationRules['input_2'] = 'required|integer|min:0';
                $validationRules['target'] = 'required|numeric|min:0|max:100';
            }

            $validationRules = array_merge($validationRules, [
                'hambatan' => 'nullable|string',
                'rekomendasi' => 'nullable|string',
                'tindak_lanjut' => 'nullable|string',
                'keberhasilan' => 'nullable|string',
            ]);

            $validated = $request->validate($validationRules);

            $model = $this->getModel($validated['jenis']);
            $data = $model::findOrFail($id);

            $input1 = (int) $validated['input_1'];
            $input2 = $validated['tipe_input'] == 'dua_input' ? (int) $validated['input_2'] : null;
            $tipe_input = $validated['tipe_input'];

            if ($input1 < 0) $input1 = 0;
            if ($input2 !== null && $input2 < 0) $input2 = 0;
            if ($tipe_input == 'dua_input' && $input2 > $input1) $input2 = $input1;

            $realisasi = 0;
            $capaian = 0;

            if ($tipe_input == 'dua_input') {
                if ($input1 > 0) {
                    $realisasi = ($input2 / $input1) * 100;
                }

                $target = $user->isSuperAdmin() ? (float) $validated['target'] : (float) $data->target;

                if ($target > 0) {
                    $capaian = ($realisasi / $target) * 100;
                }
            } else {
                $target = $user->isSuperAdmin() ? (float) $validated['target'] : (float) $data->target;
                
                if ($target > 0) {
                    $capaian = ($input1 / $target) * 100;
                }
                $realisasi = null;
            }

            $realisasi = $realisasi !== null ? round($realisasi, 2) : null;
            $capaian = round($capaian, 2);
            $status_capaian = $this->tentukanStatusCapaian($capaian);

            $updateData = [
                'input_1' => $input1,
                'input_2' => $input2,
                'realisasi' => $realisasi,
                'capaian' => $capaian,
                'status_capaian' => $status_capaian,
                'tipe_input' => $tipe_input,
                'hambatan' => $validated['hambatan'] ?? null,
                'rekomendasi' => $validated['rekomendasi'] ?? null,
                'tindak_lanjut' => $validated['tindak_lanjut'] ?? null,
                'keberhasilan' => $validated['keberhasilan'] ?? null,
            ];

            if ($user->isSuperAdmin()) {
                $updateData = array_merge($updateData, [
                    'sasaran_strategis' => $validated['sasaran_strategis'],
                    'indikator_kinerja' => $validated['indikator_kinerja'],
                    'target' => $target,
                    'rumus' => $validated['rumus'],
                    'label_input_1' => $validated['label_input_1'],
                    'label_input_2' => $validated['label_input_2'] ?? null,
                    'bulan' => (int) $validated['bulan'],
                    'tahun' => (int) $validated['tahun'],
                ]);
            }

            $data->update($updateData);

            DB::commit();

            return redirect()->back()->with('success', 'Data berhasil diupdate!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error: ' . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        
        try {
            $jenis = $request->jenis;
            
            if (!$jenis) {
                return redirect()->back()->with('error', 'Jenis data tidak ditemukan!');
            }

            $model = $this->getModel($jenis);
            $data = $model::find($id);

            if ($data) {
                $this->hapusLampiran($jenis, $data);
                
                $data->delete();
                DB::commit();
                return redirect()->back()->with('success', 'Data berhasil dihapus!');
            }

            DB::commit();
            return redirect()->back()->with('error', 'Data tidak ditemukan!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function hapusLampiran($jenis, $data)
    {
        $lampiranModels = [
            'perdata' => PerdataLampiran::class,
            'pidana' => PidanaLampiran::class,
            'tipikor' => TipikorLampiran::class,
            'phi' => PhiLampiran::class,
            'hukum' => HukumLampiran::class,
        ];

        if (isset($lampiranModels[$jenis])) {
            $lampiranModel = $lampiranModels[$jenis];
            $lampirans = $lampiranModel::where("{$jenis}_id", $data->id)->get();
            
            foreach ($lampirans as $lampiran) {
                Storage::disk('public')->delete($lampiran->path);
                $lampiran->delete();
            }
        }
    }

    // ==================== LAMPIRAN METHODS ====================
    
    public function showLampiran(Request $request, $jenis)
    {
        try {
            $user = auth()->user();
            $bulan = $request->query('bulan');
            $tahun = $request->query('tahun');
            
            $modelMapping = $this->getLampiranModelMapping();
            if (!isset($modelMapping[$jenis])) {
                return response()->json(['error' => 'Jenis tidak valid'], 400);
            }

            $modelClass = $modelMapping[$jenis];
            $relation = $jenis;
            
            $query = $modelClass::with([$relation, 'user']);
            
            if (!$user->isSuperAdmin()) {
                $query->where('user_id', $user->id);
            }
            
            if ($bulan || $tahun) {
                $query->whereHas($relation, function($q) use ($bulan, $tahun) {
                    if ($bulan) {
                        $q->where('bulan', $bulan);
                    }
                    if ($tahun) {
                        $q->where('tahun', $tahun);
                    }
                });
            }

            $lampirans = $query->orderBy('created_at', 'desc')->get();

            return response()->json($lampirans);
            
        } catch (\Exception $e) {
            Log::error('Error showing lampiran: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function storeLampiran(Request $request, $jenis)
    {
        try {
            $user = auth()->user();
            
            $validated = $request->validate([
                'parent_id' => 'required|integer',
                'lampiran' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120',
            ]);

            $modelMapping = $this->getModelMapping();
            if (!isset($modelMapping[$jenis])) {
                return response()->json(['error' => 'Jenis tidak valid'], 400);
            }

            $parentModel = $modelMapping[$jenis];
            $parent = $parentModel::findOrFail($validated['parent_id']);
            
            if (!$user->isSuperAdmin() && !in_array($user->role, [$jenis, 'super_admin'])) {
                return response()->json(['error' => 'Anda tidak memiliki akses untuk mengupload lampiran di bagian ini.'], 403);
            }

            $file = $request->file('lampiran');
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . $originalName;
            $path = $file->storeAs("{$jenis}_lampiran", $fileName, 'public');

            $lampiranModelMapping = $this->getLampiranModelMapping();
            $lampiranModel = $lampiranModelMapping[$jenis];

            $lampiran = $lampiranModel::create([
                "{$jenis}_id" => $parent->id,
                'user_id' => $user->id,
                'nama_file' => $fileName,
                'path' => $path,
                'original_name' => $originalName,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lampiran berhasil diupload.',
                'data' => $lampiran->load('user', $jenis)
            ]);

        } catch (\Exception $e) {
            Log::error('Error uploading lampiran: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function updateLampiran(Request $request, $jenis, $id)
    {
        try {
            $user = auth()->user();
            
            $lampiranModelMapping = $this->getLampiranModelMapping();
            if (!isset($lampiranModelMapping[$jenis])) {
                return response()->json(['error' => 'Jenis tidak valid'], 400);
            }

            $lampiranModel = $lampiranModelMapping[$jenis];
            $lampiran = $lampiranModel::findOrFail($id);
            
            if (!$user->isSuperAdmin() && $lampiran->user_id !== $user->id) {
                return response()->json(['error' => 'Anda tidak memiliki akses untuk mengedit lampiran ini.'], 403);
            }

            $validated = $request->validate([
                'original_name' => 'required|string|max:255',
                'keterangan' => 'nullable|string|max:500',
            ]);

            $lampiran->update([
                'original_name' => $validated['original_name'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lampiran berhasil diupdate.',
                'data' => $lampiran
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating lampiran: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function destroyLampiran($jenis, $id)
    {
        try {
            $user = auth()->user();
            
            $lampiranModelMapping = $this->getLampiranModelMapping();
            if (!isset($lampiranModelMapping[$jenis])) {
                return response()->json(['error' => 'Jenis tidak valid'], 400);
            }

            $lampiranModel = $lampiranModelMapping[$jenis];
            $lampiran = $lampiranModel::findOrFail($id);
            
            if (!$user->isSuperAdmin() && $lampiran->user_id !== $user->id) {
                return response()->json(['error' => 'Anda tidak memiliki akses untuk menghapus lampiran ini.'], 403);
            }

            Storage::disk('public')->delete($lampiran->path);
            $lampiran->delete();

            return response()->json([
                'success' => true,
                'message' => 'Lampiran berhasil dihapus.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting lampiran: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function downloadLampiran($jenis, $id)
    {
        try {
            $lampiranModelMapping = $this->getLampiranModelMapping();
            if (!isset($lampiranModelMapping[$jenis])) {
                abort(404, 'Jenis tidak valid.');
            }

            $lampiranModel = $lampiranModelMapping[$jenis];
            $lampiran = $lampiranModel::findOrFail($id);
            
            if (!Storage::disk('public')->exists($lampiran->path)) {
                abort(404, 'File tidak ditemukan.');
            }

            $user = auth()->user();
            if (!$user->isSuperAdmin() && $lampiran->user_id !== $user->id) {
                abort(403, 'Anda tidak memiliki akses untuk mendownload lampiran ini.');
            }

            $path = Storage::disk('public')->path($lampiran->path);
            return response()->download($path, $lampiran->original_name);
            
        } catch (\Exception $e) {
            Log::error('Error downloading lampiran: ' . $e->getMessage());
            abort(404, 'File tidak ditemukan.');
        }
    }

    public function getEditData($jenis, $id)
    {
        try {
            $user = auth()->user();
            
            $modelMapping = $this->getModelMapping();
            
            if (!array_key_exists($jenis, $modelMapping)) {
                return response()->json(['success' => false, 'error' => 'Jenis data tidak valid'], 400);
            }

            $model = $modelMapping[$jenis];
            $data = $model::findOrFail($id);
            
            if (!$user->isSuperAdmin() && $user->role !== $jenis) {
                return response()->json(['success' => false, 'error' => 'Anda tidak memiliki akses untuk mengedit data ini.'], 403);
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'is_super_admin' => $user->isSuperAdmin()
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting edit data: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Data tidak ditemukan'], 404);
        }
    }

    public function getAnalisisData($id, Request $request)
    {
        try {
            $jenis = $request->query('jenis', 'perdata');
            
            $models = $this->getModelMapping();
            
            if (!array_key_exists($jenis, $models)) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Jenis tidak valid'
                ]);
            }
            
            $model = $models[$jenis];
            $data = $model::find($id);
            
            if (!$data) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Data tidak ditemukan'
                ]);
            }
            
            return response()->json([
                'success' => true,
                'hambatan' => $data->hambatan,
                'rekomendasi' => $data->rekomendasi,
                'tindak_lanjut' => $data->tindak_lanjut,
                'keberhasilan' => $data->keberhasilan
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting analisis data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data'
            ], 500);
        }
    }

    private function getModel($jenis)
    {
        $models = $this->getModelMapping();
        
        if (!array_key_exists($jenis, $models)) {
            abort(404, 'Model tidak ditemukan untuk jenis: ' . $jenis);
        }

        return $models[$jenis];
    }

    private function getModelMapping()
    {
        return [
            'perdata' => Perdata::class,
            'pidana' => Pidana::class,
            'tipikor' => Tipikor::class,
            'phi' => Phi::class,
            'hukum' => Hukum::class,
        ];
    }

    private function getLampiranModelMapping()
    {
        return [
            'perdata' => PerdataLampiran::class,
            'pidana' => PidanaLampiran::class,
            'tipikor' => TipikorLampiran::class,
            'phi' => PhiLampiran::class,
            'hukum' => HukumLampiran::class,
        ];
    }
}