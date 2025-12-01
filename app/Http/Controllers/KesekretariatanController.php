<?php
// app/Http/Controllers/KesekretariatanController.php

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
        
        if (!$user->isSuperAdmin() && $user->role !== $dbRole) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        $model = $modelMapping[$jenis];
        
        $data = $model::orderBy('tahun', 'desc')
                     ->orderBy('bulan', 'desc')
                     ->orderBy('created_at', 'desc')
                     ->get();

        return view("admin.kesekretariatan.{$jenis}", compact('data', 'jenis'));
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

                // Buat array data untuk sasaran strategis baru
                $dataArray = [
                    'sasaran_strategis' => $validated['sasaran_strategis'],
                    'indikator_kinerja' => $validated['indikator_kinerja'],
                    'target' => (float) $validated['target'],
                    'label_input_1' => $validated['label_input_1'],
                    'input_1' => null,
                    'bulan' => $validated['bulan'],
                    'tahun' => $validated['tahun'],
                ];
            } else {
                // Input Data (Admin Biasa atau Super Admin)
                $validated = $request->validate([
                    'parent_id' => 'required|exists:' . $this->getTableName($jenis) . ',id',
                    'input_1' => 'required|integer|min:0',
                    'bulan' => 'required|integer|min:1|max:12',
                    'tahun' => 'required|integer|min:2000|max:2100',
                ]);

                $parentModel = $this->getParentModel($jenis);
                $parent = $parentModel::findOrFail($validated['parent_id']);
                
                $dataArray = [
                    'sasaran_strategis' => $parent->sasaran_strategis,
                    'indikator_kinerja' => $parent->indikator_kinerja,
                    'target' => $parent->target,
                    'label_input_1' => $parent->label_input_1,
                    'input_1' => $validated['input_1'],
                    'bulan' => $validated['bulan'],
                    'tahun' => $validated['tahun'],
                ];
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
            
            // Cek apakah data untuk bulan dan tahun ini sudah ada
            $existingData = $model::where('bulan', $dataArray['bulan'])
                ->where('tahun', $dataArray['tahun'])
                ->where('sasaran_strategis', $dataArray['sasaran_strategis'])
                ->first();

            if ($existingData) {
                // Update data yang sudah ada
                $existingData->update($dataArray);
                $data = $existingData;
            } else {
                // Buat data baru
                $data = $model::create($dataArray);
            }

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
        DB::beginTransaction();
        
        try {
            $user = auth()->user();
            
            $path = $request->path();
            $jenis = $this->getJenisFromPath($path);
            $dbRole = $this->getDbRoleFromUrl($jenis);

            if (!$user->isSuperAdmin() && $user->role !== $dbRole) {
                abort(403, 'Anda tidak memiliki akses untuk mengupdate data di bagian ini.');
            }

            // Validasi berbeda untuk Super Admin dan Admin Biasa
            if ($user->isSuperAdmin()) {
                $validated = $request->validate([
                    'sasaran_strategis' => 'required|string|max:255',
                    'indikator_kinerja' => 'required|string|max:255',
                    'target' => 'required|numeric|min:0|max:100',
                    'label_input_1' => 'required|string|max:255',
                    'input_1' => 'required|integer|min:0',
                    'bulan' => 'required|integer|min:1|max:12',
                    'tahun' => 'required|integer|min:2000|max:2100',
                ]);
            } else {
                // Admin biasa hanya bisa update input_1
                $validated = $request->validate([
                    'input_1' => 'required|integer|min:0',
                ]);
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

            // Admin biasa hanya bisa update input_1
            if (!$user->isSuperAdmin()) {
                $data->update([
                    'input_1' => $validated['input_1'],
                ]);
            } else {
                $data->update($validated);
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

            if (!$user->isSuperAdmin() && $user->role !== $dbRole) {
                abort(403, 'Anda tidak memiliki akses untuk menghapus data di bagian ini.');
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

    // Helper methods
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
}