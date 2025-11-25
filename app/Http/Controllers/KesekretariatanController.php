<?php

namespace App\Http\Controllers;

use App\Models\Ptip;
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
            'ptip' => Ptip::class,
            'umum-keuangan' => UmumKeuangan::class,
            'kepegawaian' => Kepegawaian::class,
        ];

        if (!array_key_exists($jenis, $modelMapping)) {
            abort(404);
        }

        // Cek authorization - PERBAIKAN: gunakan mapping yang benar
        $user = auth()->user();
        $dbRole = $this->getDbRoleFromUrl($jenis);
        
        // Super admin bisa akses semua, admin biasa hanya akses role mereka
        if (!$user->isSuperAdmin() && $user->role !== $dbRole) {
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
            
            // Dapatkan jenis dari URL path
            $path = $request->path();
            $jenis = $this->getJenisFromPath($path);
            $dbRole = $this->getDbRoleFromUrl($jenis);

            // Cek authorization sebelum menyimpan - PERBAIKAN: gunakan mapping yang benar
            if (!$user->isSuperAdmin() && $user->role !== $dbRole) {
                abort(403, 'Anda tidak memiliki akses untuk menyimpan data di bagian ini.');
            }

            // Validasi berbeda untuk Super Admin dan Admin Biasa
            if ($user->isSuperAdmin()) {
                $validated = $request->validate([
                    'sasaran_strategis' => 'required|string|max:255',
                    'indikator_kinerja' => 'required|string|max:255',
                    'target' => 'required|numeric|min:0|max:100',
                    'rumus' => 'required|string|max:500',
                    'label_input_1' => 'required|string|max:255',
                    'label_input_2' => 'required|string|max:255',
                    'bulan' => 'required|integer|min:1|max:12',
                    'tahun' => 'required|integer|min:2000|max:2100',
                    'input_1' => 'nullable|integer|min:0',
                    'input_2' => 'nullable|integer|min:0',
                ]);
            } else {
                $validated = $request->validate([
                    'sasaran_strategis' => 'required|string|max:255',
                    'indikator_kinerja' => 'required|string|max:255',
                    'target' => 'required|numeric|min:0|max:100',
                    'rumus' => 'required|string|max:500',
                    'input_1' => 'required|integer|min:0',
                    'input_2' => 'required|integer|min:0',
                    'label_input_1' => 'sometimes|string|max:255',
                    'label_input_2' => 'sometimes|string|max:255',
                    'bulan' => 'required|integer|min:1|max:12',
                    'tahun' => 'required|integer|min:2000|max:2100',
                ]);
            }

            // Handle nilai null/empty dan pastikan tipe data numerik
            $input1 = isset($validated['input_1']) ? (int) $validated['input_1'] : 0;
            $input2 = isset($validated['input_2']) ? (int) $validated['input_2'] : 0;
            $target = (float) $validated['target'];

            // Set default labels jika tidak disediakan
            $label1 = $validated['label_input_1'] ?? $this->getDefaultLabel1($jenis);
            $label2 = $validated['label_input_2'] ?? $this->getDefaultLabel2($jenis);

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
                'ptip' => Ptip::class,
                'umum-keuangan' => UmumKeuangan::class,
                'kepegawaian' => Kepegawaian::class,
            ];

            if (!array_key_exists($jenis, $modelMapping)) {
                throw new \Exception('Jenis data tidak valid');
            }

            $model = $modelMapping[$jenis];
            $data = $model::create([
                'sasaran_strategis' => $validated['sasaran_strategis'],
                'indikator_kinerja' => $validated['indikator_kinerja'],
                'target' => $target,
                'rumus' => $validated['rumus'],
                'label_input_1' => $label1,
                'label_input_2' => $label2,
                'input_1' => $input1,
                'input_2' => $input2,
                'realisasi' => $realisasi,
                'capaian' => $capaian,
                'bulan' => $validated['bulan'],
                'tahun' => $validated['tahun'],
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
        DB::beginTransaction();
        
        try {
            $user = auth()->user();
            
            // Dapatkan jenis dari URL path
            $path = $request->path();
            $jenis = $this->getJenisFromPath($path);
            $dbRole = $this->getDbRoleFromUrl($jenis);

            // Cek authorization sebelum update - PERBAIKAN: gunakan mapping yang benar
            if (!$user->isSuperAdmin() && $user->role !== $dbRole) {
                abort(403, 'Anda tidak memiliki akses untuk mengupdate data di bagian ini.');
            }

            // Validasi
            $validated = $request->validate([
                'sasaran_strategis' => 'required|string|max:255',
                'indikator_kinerja' => 'required|string|max:255',
                'target' => 'required|numeric|min:0|max:100',
                'rumus' => 'required|string|max:500',
                'label_input_1' => 'required|string|max:255',
                'label_input_2' => 'required|string|max:255',
                'bulan' => 'required|integer|min:1|max:12',
                'tahun' => 'required|integer|min:2000|max:2100',
            ]);

            // Pastikan target bertipe float
            $validated['target'] = (float) $validated['target'];

            // Cari dan update data
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

            $data->update($validated);

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
            
            // Dapatkan jenis dari URL path
            $path = $request->path();
            $jenis = $this->getJenisFromPath($path);
            $dbRole = $this->getDbRoleFromUrl($jenis);

            // Cek authorization sebelum delete - PERBAIKAN: gunakan mapping yang benar
            if (!$user->isSuperAdmin() && $user->role !== $dbRole) {
                abort(403, 'Anda tidak memiliki akses untuk menghapus data di bagian ini.');
            }

            // Cari data di model yang sesuai
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

    // Helper untuk mapping URL ke nilai database - PERBAIKAN: mapping yang benar
    private function getDbRoleFromUrl($urlJenis)
    {
        $mapping = [
            'ptip' => 'ptip',
            'umum-keuangan' => 'umum_keuangan', // PERBAIKAN: underscore untuk database
            'kepegawaian' => 'kepegawaian'
        ];

        return $mapping[$urlJenis] ?? $urlJenis;
    }

    // Helper method untuk mendapatkan jenis dari path URL
    private function getJenisFromPath($path)
    {
        $path = trim($path, '/');
        $segments = explode('/', $path);
        
        // Ambil segment pertama sebagai jenis
        return $segments[0] ?? null;
    }

    // Helper method untuk default label 1 berdasarkan jenis
    private function getDefaultLabel1($jenis)
    {
        $labels = [
            'ptip' => 'Jumlah Kegiatan PTIP',
            'umum-keuangan' => 'Jumlah Proses Umum & Keuangan',
            'kepegawaian' => 'Jumlah Proses Kepegawaian'
        ];

        return $labels[$jenis] ?? 'Jumlah Kegiatan';
    }

    // Helper method untuk default label 2 berdasarkan jenis
    private function getDefaultLabel2($jenis)
    {
        $labels = [
            'ptip' => 'Jumlah Kegiatan Tepat Waktu',
            'umum-keuangan' => 'Jumlah Proses Tepat Waktu',
            'kepegawaian' => 'Jumlah Proses Tepat Waktu'
        ];

        return $labels[$jenis] ?? 'Jumlah Tepat Waktu';
    }
}