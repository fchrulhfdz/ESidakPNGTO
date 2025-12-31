<?php

namespace App\Http\Controllers;

use App\Models\Ptip;
use App\Models\PtipLampiran;
use App\Models\UmumKeuangan;
use App\Models\UmumKeuanganLampiran;
use App\Models\Kepegawaian;
use App\Models\KepegawaianLampiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class KesekretariatanController extends Controller
{
    public function show(Request $request)
    {
        $path = $request->path();
        $jenis = $this->getJenisFromPath($path);
        
        $modelMapping = [
            'ptip' => Ptip::class,
            'umum-keuangan' => UmumKeuangan::class,
            'kepegawaian' => Kepegawaian::class,
        ];

         if (!array_key_exists($jenis, $modelMapping)) {
        abort(404);
        }

        $user = auth()->user();
        $dbRole = $this->getDbRoleFromUrl($jenis);
        
        // PERBAIKAN: Izinkan akses untuk:
        // 1. Super admin
        // 2. Role yang sesuai dengan jenis 
        // 3. Read_only (boleh lihat semua)
        $allowedRoles = ['super_admin', 'admin', $dbRole, 'read_only'];
        
        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $model = $modelMapping[$jenis];
        
        $data = $model::orderBy('tahun', 'desc')
                    ->orderBy('bulan', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        // Ambil sasaran strategis unik untuk dropdown (hanya yang belum ada inputnya)
        $sasaranStrategis = $model::whereNull('input_1')
                                 ->select('sasaran_strategis', 'id', 'indikator_kinerja', 'target', 'label_input_1')
                                 ->distinct('sasaran_strategis')
                                 ->orderBy('sasaran_strategis')
                                 ->get();

        return view("admin.kesekretariatan.{$jenis}", compact('data', 'jenis', 'sasaranStrategis'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $user = auth()->user();
            
            $path = $request->path();
            $jenis = $this->getJenisFromPath($path);
            $dbRole = $this->getDbRoleFromUrl($jenis);

            if (!$user->isSuperAdmin() && $user->role !== $dbRole) {
                abort(403, 'Anda tidak memiliki akses untuk menyimpan data di bagian ini.');
            }

            // Deteksi jenis input: sasaran strategis baru atau input data
            if ($request->has('sasaran_strategis') && !$request->has('parent_id')) {
                // Input Sasaran Strategis Baru (hanya Super Admin)
                if (!$user->isSuperAdmin()) {
                    abort(403, 'Hanya Super Admin yang dapat menambah sasaran strategis.');
                }

                $validated = $request->validate([
                    'sasaran_strategis' => 'required|string|max:255',
                    'indikator_kinerja' => 'required|string|max:255',
                    'target' => 'required|numeric|min:0|max:100',
                    'label_input_1' => 'required|string|max:255',
                    'bulan' => 'required|integer|min:1|max:12',
                    'tahun' => 'required|integer|min:2000|max:2100',
                ]);

                $dataArray = [
                    'sasaran_strategis' => $validated['sasaran_strategis'],
                    'indikator_kinerja' => $validated['indikator_kinerja'],
                    'target' => (float) $validated['target'],
                    'label_input_1' => $validated['label_input_1'],
                    'input_1' => null,
                    'capaian' => null,
                    'status_capaian' => null,
                    'bulan' => $validated['bulan'],
                    'tahun' => $validated['tahun'],
                ];

                $modelMapping = [
                    'ptip' => Ptip::class,
                    'umum-keuangan' => UmumKeuangan::class,
                    'kepegawaian' => Kepegawaian::class,
                ];

                if (!array_key_exists($jenis, $modelMapping)) {
                    throw new \Exception('Jenis data tidak valid');
                }

                $model = $modelMapping[$jenis];
                
                $data = $model::create($dataArray);

                DB::commit();

                return redirect()->back()->with('success', 'Sasaran strategis berhasil ditambahkan!');

            } else {
                // Input Data (Admin Biasa atau Super Admin) - dengan perhitungan capaian
                $validated = $request->validate([
                    'parent_id' => 'required|exists:' . $this->getTableName($jenis) . ',id',
                    'input_1' => 'required|integer|min:0',
                    'bulan' => 'required|integer|min:1|max:12',
                    'tahun' => 'required|integer|min:2000|max:2100',
                    'hambatan' => 'nullable|string',
                    'rekomendasi' => 'nullable|string',
                    'tindak_lanjut' => 'nullable|string',
                    'keberhasilan' => 'nullable|string',
                ]);

                $parentModel = $this->getParentModel($jenis);
                $parent = $parentModel::findOrFail($validated['parent_id']);
                
                // Pastikan parent adalah template sasaran strategis (input_1 null)
                if (!is_null($parent->input_1)) {
                    return redirect()->back()->with('error', 'Data yang dipilih bukan template sasaran strategis.');
                }
                
                $modelMapping = [
                    'ptip' => Ptip::class,
                    'umum-keuangan' => UmumKeuangan::class,
                    'kepegawaian' => Kepegawaian::class,
                ];

                if (!array_key_exists($jenis, $modelMapping)) {
                    throw new \Exception('Jenis data tidak valid');
                }

                $model = $modelMapping[$jenis];
                
                // Cari data yang sudah ada (baik yang sudah diisi maupun belum)
                $existingData = $model::where('bulan', $validated['bulan'])
                    ->where('tahun', $validated['tahun'])
                    ->where('sasaran_strategis', $parent->sasaran_strategis)
                    ->first();

                // Hitung capaian menggunakan method helper
                $capaianResult = $this->hitungCapaian(
                    (float)$validated['input_1'],
                    (float)$parent->target
                );

                $dataArray = [
                    'sasaran_strategis' => $parent->sasaran_strategis,
                    'indikator_kinerja' => $parent->indikator_kinerja,
                    'target' => $parent->target,
                    'label_input_1' => $parent->label_input_1,
                    'input_1' => $validated['input_1'],
                    'capaian' => $capaianResult['capaian'],
                    'status_capaian' => $capaianResult['status'],
                    'hambatan' => $validated['hambatan'] ?? null,
                    'rekomendasi' => $validated['rekomendasi'] ?? null,
                    'tindak_lanjut' => $validated['tindak_lanjut'] ?? null,
                    'keberhasilan' => $validated['keberhasilan'] ?? null,
                    'bulan' => $validated['bulan'],
                    'tahun' => $validated['tahun'],
                ];

                if ($existingData) {
                    // UPDATE data yang sudah ada
                    $existingData->update($dataArray);
                    $data = $existingData;
                } else {
                    // Buat data baru
                    $data = $model::create($dataArray);
                }

                DB::commit();

                return redirect()->back()->with('success', 'Data berhasil ' . ($existingData ? 'diupdate' : 'disimpan') . '!');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        
        try {
            $user = auth()->user();
            
            $path = $request->path();
            $jenis = $this->getJenisFromPath($path);
            $dbRole = $this->getDbRoleFromUrl($jenis);

            // Validasi akses: Super Admin atau Admin dengan role yang sesuai
            if (!$user->isSuperAdmin() && $user->role !== $dbRole) {
                abort(403, 'Anda tidak memiliki akses untuk mengupdate data di bagian ini.');
            }

            $modelMapping = [
                'ptip' => Ptip::class,
                'umum-keuangan' => UmumKeuangan::class,
                'kepegawaian' => Kepegawaian::class,
            ];

            if (!array_key_exists($jenis, $modelMapping)) {
                throw new \Exception('Jenis data tidak valid');
            }

            $model = $modelMapping[$jenis];
            $data = $model::findOrFail($id);

            // Validasi berbeda untuk Super Admin dan Admin Biasa
            if ($user->isSuperAdmin()) {
                $validated = $request->validate([
                    'sasaran_strategis' => 'required|string|max:255',
                    'indikator_kinerja' => 'required|string|max:255',
                    'target' => 'required|numeric|min:0|max:100',
                    'label_input_1' => 'required|string|max:255',
                    'input_1' => 'required|numeric|min:0',
                    'bulan' => 'required|integer|min:1|max:12',
                    'tahun' => 'required|integer|min:2000|max:2100',
                    'hambatan' => 'nullable|string',
                    'rekomendasi' => 'nullable|string',
                    'tindak_lanjut' => 'nullable|string',
                    'keberhasilan' => 'nullable|string',
                ]);
                
                // Hitung capaian untuk super admin
                $capaianResult = $this->hitungCapaian(
                    (float)$validated['input_1'],
                    (float)$validated['target']
                );
                
                $updateData = array_merge($validated, [
                    'capaian' => $capaianResult['capaian'],
                    'status_capaian' => $capaianResult['status'],
                ]);
                
                $data->update($updateData);
                
            } else {
                // Admin biasa hanya bisa update input_1 dan kolom analisis
                $validated = $request->validate([
                    'input_1' => 'required|numeric|min:0',
                    'hambatan' => 'nullable|string',
                    'rekomendasi' => 'nullable|string',
                    'tindak_lanjut' => 'nullable|string',
                    'keberhasilan' => 'nullable|string',
                ]);
                
                // Gunakan nilai yang tidak bisa diubah oleh admin biasa
                $target = $data->target;
                $input1 = (float)$validated['input_1'];
                
                // Hitung capaian menggunakan target dari data yang ada
                $capaianResult = $this->hitungCapaian($input1, $target);
                
                $updateData = [
                    'input_1' => $input1,
                    'capaian' => $capaianResult['capaian'],
                    'status_capaian' => $capaianResult['status'],
                    'hambatan' => $validated['hambatan'] ?? $data->hambatan,
                    'rekomendasi' => $validated['rekomendasi'] ?? $data->rekomendasi,
                    'tindak_lanjut' => $validated['tindak_lanjut'] ?? $data->tindak_lanjut,
                    'keberhasilan' => $validated['keberhasilan'] ?? $data->keberhasilan,
                ];
                
                $data->update($updateData);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil diupdate!');

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
            $user = auth()->user();
            
            $path = $request->path();
            $jenis = $this->getJenisFromPath($path);
            $dbRole = $this->getDbRoleFromUrl($jenis);

            // Hanya Super Admin yang bisa menghapus data
            if (!$user->isSuperAdmin()) {
                abort(403, 'Hanya Super Admin yang dapat menghapus data.');
            }

            $modelMapping = [
                'ptip' => Ptip::class,
                'umum-keuangan' => UmumKeuangan::class,
                'kepegawaian' => Kepegawaian::class,
            ];

            if (!array_key_exists($jenis, $modelMapping)) {
                return redirect()->back()->with('error', 'Jenis data tidak valid!');
            }

            $model = $modelMapping[$jenis];
            $data = $model::find($id);
            
            if ($data) {
                // Hapus semua lampiran terkait
                if ($jenis === 'ptip') {
                    $data->lampirans()->delete();
                } elseif ($jenis === 'umum-keuangan') {
                    $data->lampirans()->delete();
                } elseif ($jenis === 'kepegawaian') {
                    $data->lampirans()->delete();
                }
                
                $data->delete();
                DB::commit();
                return redirect()->back()->with('success', 'Data berhasil dihapus!');
            }

            return redirect()->back()->with('error', 'Data tidak ditemukan!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ==================== LAMPIRAN METHODS ====================
    
    public function showLampiran(Request $request)
    {
        $path = $request->path();
        $jenis = $this->getJenisFromPath($path);
        
        return $this->handleShowLampiran($request, $jenis);
    }

    private function handleShowLampiran(Request $request, $jenis)
    {
        try {
            $user = auth()->user();
            $bulan = $request->query('bulan');
            $tahun = $request->query('tahun');
            
            $modelMapping = [
                'ptip' => ['model' => PtipLampiran::class, 'relation' => 'ptip'],
                'umum-keuangan' => ['model' => UmumKeuanganLampiran::class, 'relation' => 'umumKeuangan'],
                'kepegawaian' => ['model' => KepegawaianLampiran::class, 'relation' => 'kepegawaian'],
            ];
            
            if (!array_key_exists($jenis, $modelMapping)) {
                return response()->json(['error' => 'Jenis data tidak valid'], 400);
            }
            
            $modelClass = $modelMapping[$jenis]['model'];
            $relation = $modelMapping[$jenis]['relation'];
            
            $query = $modelClass::with([$relation, 'user']);
            
            if (!$user->isSuperAdmin()) {
                $query->where('user_id', $user->id);
            }
            
            // Filter berdasarkan bulan dan tahun
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

    public function storeLampiran(Request $request)
    {
        $path = $request->path();
        $segments = explode('/', $path);
        $jenis = $segments[0] ?? null;
        
        return $this->handleStoreLampiran($request, $jenis);
    }

    private function handleStoreLampiran(Request $request, $jenis)
    {
        try {
            $user = auth()->user();
            
            $modelMapping = [
                'ptip' => ['parent' => Ptip::class, 'lampiran' => PtipLampiran::class],
                'umum-keuangan' => ['parent' => UmumKeuangan::class, 'lampiran' => UmumKeuanganLampiran::class],
                'kepegawaian' => ['parent' => Kepegawaian::class, 'lampiran' => KepegawaianLampiran::class],
            ];
            
            if (!array_key_exists($jenis, $modelMapping)) {
                return response()->json(['error' => 'Jenis data tidak valid'], 400);
            }
            
            $validated = $request->validate([
                'parent_id' => 'required|exists:' . $this->getTableName($jenis) . ',id',
                'lampiran' => 'required|file|mimes:pdf|max:5120',
            ]);

            $parentModel = $modelMapping[$jenis]['parent'];
            $parent = $parentModel::findOrFail($validated['parent_id']);
            
            // Cek akses berdasarkan role
            $roleMapping = [
                'ptip' => 'ptip',
                'umum-keuangan' => 'umum_keuangan',
                'kepegawaian' => 'kepegawaian'
            ];
            
            if (!$user->isSuperAdmin() && !in_array($user->role, [$roleMapping[$jenis], 'super_admin'])) {
                return response()->json(['error' => 'Anda tidak memiliki akses untuk mengupload lampiran di bagian ini.'], 403);
            }

            $file = $request->file('lampiran');
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . $originalName;
            $path = $file->storeAs($jenis . '_lampiran', $fileName, 'public');

            $lampiranModel = $modelMapping[$jenis]['lampiran'];
            $lampiran = $lampiranModel::create([
                $this->getForeignKey($jenis) => $parent->id,
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
                'data' => $lampiran->load('user', $this->getRelationName($jenis))
            ]);

        } catch (\Exception $e) {
            Log::error('Error uploading lampiran: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function updateLampiran(Request $request, $id)
    {
        $path = $request->path();
        $segments = explode('/', $path);
        $jenis = $segments[0] ?? null;
        
        return $this->handleUpdateLampiran($request, $jenis, $id);
    }

    private function handleUpdateLampiran(Request $request, $jenis, $id)
    {
        try {
            $user = auth()->user();
            
            $modelMapping = [
                'ptip' => PtipLampiran::class,
                'umum-keuangan' => UmumKeuanganLampiran::class,
                'kepegawaian' => KepegawaianLampiran::class,
            ];
            
            if (!array_key_exists($jenis, $modelMapping)) {
                return response()->json(['error' => 'Jenis data tidak valid'], 400);
            }
            
            $modelClass = $modelMapping[$jenis];
            $lampiran = $modelClass::findOrFail($id);
            
            if (!$user->isSuperAdmin() && $lampiran->user_id !== $user->id) {
                return response()->json(['error' => 'Anda tidak memiliki akses untuk mengedit lampiran ini.'], 403);
            }

            $validated = $request->validate([
                'original_name' => 'required|string|max:255',
            ]);

            $lampiran->update([
                'original_name' => $validated['original_name'],
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

    public function destroyLampiran($id)
    {
        $path = request()->path();
        $segments = explode('/', $path);
        $jenis = $segments[0] ?? null;
        
        return $this->handleDestroyLampiran($jenis, $id);
    }

    private function handleDestroyLampiran($jenis, $id)
    {
        try {
            $user = auth()->user();
            
            $modelMapping = [
                'ptip' => PtipLampiran::class,
                'umum-keuangan' => UmumKeuanganLampiran::class,
                'kepegawaian' => KepegawaianLampiran::class,
            ];
            
            if (!array_key_exists($jenis, $modelMapping)) {
                return response()->json(['error' => 'Jenis data tidak valid'], 400);
            }
            
            $modelClass = $modelMapping[$jenis];
            $lampiran = $modelClass::findOrFail($id);
            
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

    public function downloadLampiran($id)
    {
        $path = request()->path();
        $segments = explode('/', $path);
        $jenis = $segments[0] ?? null;
        
        return $this->handleDownloadLampiran($jenis, $id);
    }

    private function handleDownloadLampiran($jenis, $id)
    {
        try {
            $modelMapping = [
                'ptip' => PtipLampiran::class,
                'umum-keuangan' => UmumKeuanganLampiran::class,
                'kepegawaian' => KepegawaianLampiran::class,
            ];
            
            if (!array_key_exists($jenis, $modelMapping)) {
                abort(404, 'Jenis data tidak valid.');
            }
            
            $modelClass = $modelMapping[$jenis];
            $lampiran = $modelClass::findOrFail($id);
            
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

    // ==================== API UNTUK PERHITUNGAN CAPAIAN ====================
    
    /**
     * API untuk perhitungan capaian real-time
     */
    public function calculateCapaianApi(Request $request)
    {
        try {
            $validated = $request->validate([
                'input_1' => 'required|numeric|min:0',
                'target' => 'required|numeric|min:0|max:100',
            ]);

            $result = $this->hitungCapaian(
                (float) $validated['input_1'],
                (float) $validated['target']
            );

            return response()->json([
                'success' => true,
                'capaian' => number_format($result['capaian'], 2), // Format 2 desimal
                'capaian_raw' => $result['capaian'], // Nilai mentah
                'status' => $result['status'],
                'persentase' => $result['persentase'],
                'progress_width' => $result['progress_width']
            ]);

        } catch (\Exception $e) {
            Log::error('Error calculating capaian: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API untuk mendapatkan data edit
     */
    public function getEditData($jenis, $id)
    {
        try {
            $user = auth()->user();
            
            $modelMapping = [
                'ptip' => Ptip::class,
                'umum-keuangan' => UmumKeuangan::class,
                'kepegawaian' => Kepegawaian::class,
            ];

            if (!array_key_exists($jenis, $modelMapping)) {
                return response()->json(['success' => false, 'error' => 'Jenis data tidak valid'], 400);
            }

            $model = $modelMapping[$jenis];
            $data = $model::findOrFail($id);
            
            // Cek akses: Super Admin atau Admin dengan role yang sesuai
            $dbRole = $this->getDbRoleFromUrl($jenis);
            if (!$user->isSuperAdmin() && $user->role !== $dbRole) {
                return response()->json(['success' => false, 'error' => 'Anda tidak memiliki akses untuk mengedit data ini.'], 403);
            }

            // Hitung capaian jika data sudah ada
            $capaianData = [];
            if ($data->input_1 !== null && $data->target > 0) {
                $capaianData = $this->hitungCapaian($data->input_1, $data->target);
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'capaian_data' => $capaianData,
                'is_super_admin' => $user->isSuperAdmin()
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting edit data: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Data tidak ditemukan'], 404);
        }
    }

    // ==================== HELPER METHODS ====================
    
    /**
     * Hitung capaian berdasarkan input dan target (dalam persentase)
     */
    private function hitungCapaian($input, $target)
    {
        if ($target == 0) {
            return [
                'capaian' => 0,
                'status' => 'Belum Tercapai',
                'persentase' => '0%',
                'progress_width' => 0
            ];
        }

        // Hitung capaian dalam persentase: (input / target) * 100
        $capaian = ($input / $target) * 100;
        
        // Tentukan status berdasarkan persentase capaian
        if ($capaian >= 100) {
            $status = 'Tercapai';
        } elseif ($capaian >= 80) {
            $status = 'Hampir Tercapai';
        } else {
            $status = 'Belum Tercapai';
        }

        return [
            'capaian' => $capaian,
            'capaian_raw' => $capaian, // Nilai mentah untuk perhitungan
            'status' => $status,
            'persentase' => number_format($capaian, 2) . '%',
            'progress_width' => min($capaian, 100)
        ];
    }
    
    private function getTableName($jenis)
    {
        $mapping = [
            'ptip' => 'ptips',
            'umum-keuangan' => 'umum_keuangans',
            'kepegawaian' => 'kepegawaians',
        ];
        
        return $mapping[$jenis] ?? 'ptips';
    }
    
    private function getParentModel($jenis)
    {
        $mapping = [
            'ptip' => Ptip::class,
            'umum-keuangan' => UmumKeuangan::class,
            'kepegawaian' => Kepegawaian::class,
        ];
        
        return $mapping[$jenis] ?? Ptip::class;
    }
    
    private function getForeignKey($jenis)
    {
        $mapping = [
            'ptip' => 'ptip_id',
            'umum-keuangan' => 'umum_keuangan_id',
            'kepegawaian' => 'kepegawaian_id',
        ];
        
        return $mapping[$jenis] ?? 'ptip_id';
    }
    
    private function getRelationName($jenis)
    {
        $mapping = [
            'ptip' => 'ptip',
            'umum-keuangan' => 'umumKeuangan',
            'kepegawaian' => 'kepegawaian',
        ];
        
        return $mapping[$jenis] ?? 'ptip';
    }

    private function getDbRoleFromUrl($urlJenis)
    {
        $mapping = [
            'ptip' => 'ptip',
            'umum-keuangan' => 'umum_keuangan',
            'kepegawaian' => 'kepegawaian'
        ];

        return $mapping[$urlJenis] ?? $urlJenis;
    }

    private function getJenisFromPath($path)
    {
        $path = trim($path, '/');
        $segments = explode('/', $path);
        
        return $segments[0] ?? null;
    }

    // Method API untuk mendapatkan sasaran strategis unik
    public function getSasaranStrategis($jenis)
    {
        $modelMapping = [
            'ptip' => Ptip::class,
            'umum-keuangan' => UmumKeuangan::class,
            'kepegawaian' => Kepegawaian::class,
        ];

        if (!array_key_exists($jenis, $modelMapping)) {
            return response()->json([], 404);
        }

        $model = $modelMapping[$jenis];
        $sasaranStrategis = $model::whereNull('input_1')
                                 ->select('sasaran_strategis', 'id', 'indikator_kinerja', 'target', 'label_input_1')
                                 ->distinct('sasaran_strategis')
                                 ->orderBy('sasaran_strategis')
                                 ->get();

        return response()->json($sasaranStrategis);
    }

    // Method untuk mendapatkan data berdasarkan indikator kinerja
    public function getByIndikator($jenis, Request $request)
    {
        $modelMapping = [
            'ptip' => Ptip::class,
            'umum-keuangan' => UmumKeuangan::class,
            'kepegawaian' => Kepegawaian::class,
        ];

        if (!array_key_exists($jenis, $modelMapping)) {
            return response()->json([], 404);
        }

        $model = $modelMapping[$jenis];
        $query = $model::query();
        
        if ($request->has('indikator')) {
            $query->where('indikator_kinerja', 'like', '%' . $request->indikator . '%');
        }
        
        if ($request->has('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        
        if ($request->has('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        
        $data = $query->orderBy('tahun', 'desc')
                     ->orderBy('bulan', 'desc')
                     ->get()
                     ->map(function($item) {
                         return [
                             'id' => $item->id,
                             'sasaran_strategis' => $item->sasaran_strategis,
                             'indikator_kinerja' => $item->indikator_kinerja,
                             'nama_bulan' => $item->nama_bulan,
                             'tahun' => $item->tahun,
                             'bulan' => $item->bulan,
                             'capaian' => $item->capaian,
                             'status_capaian' => $item->status_capaian,
                         ];
                     });

        return response()->json($data);
    }

    // API untuk mendapatkan data analisis berdasarkan ID
    public function getAnalisisData($id, Request $request)
    {
        try {
            $jenis = $request->query('jenis', 'ptip');
            
            $models = [
                'ptip' => Ptip::class,
                'umum-keuangan' => UmumKeuangan::class,
                'kepegawaian' => Kepegawaian::class,
            ];
            
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
}