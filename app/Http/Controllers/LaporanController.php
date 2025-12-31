<?php

namespace App\Http\Controllers;

use App\Models\Perdata;
use App\Models\Pidana;
use App\Models\Tipikor;
use App\Models\PHI;
use App\Models\Hukum;
use App\Models\PTIP;
use App\Models\UmumKeuangan;
use App\Models\Kepegawaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $data = collect();
        $jenisLaporan = $request->jenis_laporan ?? 'bulanan'; // bulanan, tahunan, triwulan
        $bagian = $request->bagian ?? null;
        $bulan = $request->bulan ?? null;
        $tahun = $request->tahun ?? null;
        $triwulan = $request->triwulan ?? null;
        
        // Nama bulan dalam bahasa Indonesia
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        
        if ($request->has('search')) {
            $bulan = $request->bulan;
            $tahun = $request->tahun;
            $bagian = $request->bagian;
            $triwulan = $request->triwulan;

            // Ambil data berdasarkan jenis laporan
            $data = $this->getFilteredData($bulan, $tahun, $bagian, $jenisLaporan, $triwulan);
        }

        return view('laporan.index', compact('data', 'jenisLaporan', 'bagian', 'bulan', 'tahun', 'triwulan', 'namaBulan'));
    }

    public function cetakWord(Request $request)
    {
        $jenisLaporan = $request->jenis_laporan ?? 'bulanan';
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $bagian = $request->bagian;
        $triwulan = $request->triwulan;

        // Ambil semua data termasuk kolom analisis
        $data = $this->getFilteredData($bulan, $tahun, $bagian, $jenisLaporan, $triwulan);
        $user = Auth::user();
        
        // Pisahkan data berdasarkan jenis (kepanitraan vs kesekretariatan)
        $jenisKepanitraan = ['perdata', 'pidana', 'tipikor', 'phi', 'hukum'];
        $jenisKesekretariatan = ['ptip', 'umum_keuangan', 'kepegawaian'];
        
        // Filter data yang memiliki analisis
        $dataWithAnalisis = $data->filter(function($item) {
            return !empty($item->hambatan) || !empty($item->rekomendasi) || 
                   !empty($item->tindak_lanjut) || !empty($item->keberhasilan);
        });
        
        // Nama bulan dalam bahasa Indonesia
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        
        // Mapping nama bagian
        $bagianMapping = [
            'perdata' => 'Perdata',
            'pidana' => 'Pidana',
            'tipikor' => 'Tipikor',
            'phi' => 'PHI',
            'hukum' => 'Hukum',
            'ptip' => 'PTIP',
            'umum_keuangan' => 'Umum & Keuangan',
            'kepegawaian' => 'Kepegawaian'
        ];
        
        // Nama triwulan
        $namaTriwulan = [
            '1' => 'I (Januari - Maret)',
            '2' => 'II (April - Juni)',
            '3' => 'III (Juli - September)',
            '4' => 'IV (Oktober - Desember)'
        ];
        
        $filename = 'laporan-e-sidak-' . date('Y-m-d-His') . '.doc';
        
        // Return view untuk Word
        return response()
            ->view('laporan.word-template', compact(
                'data',
                'jenisKepanitraan',
                'jenisKesekretariatan',
                'dataWithAnalisis',
                'bulan',
                'tahun',
                'triwulan',
                'user',
                'jenisLaporan',
                'namaBulan',
                'bagian',
                'bagianMapping',
                'namaTriwulan'
            ))
            ->header('Content-Type', 'application/vnd.ms-word')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    private function getFilteredData($bulan, $tahun, $bagian, $jenisLaporan = 'bulanan', $triwulan = null)
    {
        $data = collect();
        $models = $this->getModels();
        $user = Auth::user();

        // Untuk user biasa, hanya tampilkan bagian sesuai role
        if ($user->role !== 'super_admin') {
            $userBagian = $user->role;
            if (array_key_exists($userBagian, $models)) {
                $modelClass = $models[$userBagian];
                $modelData = $this->queryData($modelClass, $bulan, $tahun, $userBagian, $jenisLaporan, $triwulan);
                $data = $data->merge($modelData);
            }
        } 
        // Untuk super admin
        else {
            // Jika memilih semua bagian
            if ($bagian === 'all' || !$bagian) {
                foreach ($models as $jenis => $modelClass) {
                    $modelData = $this->queryData($modelClass, $bulan, $tahun, $jenis, $jenisLaporan, $triwulan);
                    $data = $data->merge($modelData);
                }
            } 
            // Jika memilih bagian tertentu
            else if (array_key_exists($bagian, $models)) {
                $modelData = $this->queryData($models[$bagian], $bulan, $tahun, $bagian, $jenisLaporan, $triwulan);
                $data = $data->merge($modelData);
            }
        }

        // Urutkan data berdasarkan sasaran strategis dan indikator kinerja
        return $data->sortBy(function($item) {
            return ($item->sasaran_strategis ?? '') . ($item->indikator_kinerja ?? '');
        });
    }

    private function queryData($modelClass, $bulan, $tahun, $jenis, $jenisLaporan, $triwulan = null)
    {
        $query = $modelClass::query();

        // Tentukan kolom berdasarkan jenis model
        $jenisKepanitraan = ['perdata', 'pidana', 'tipikor', 'phi', 'hukum'];
        $jenisKesekretariatan = ['ptip', 'umum_keuangan', 'kepegawaian'];
        
        // Pilih kolom berdasarkan jenis model
        if (in_array($jenis, $jenisKepanitraan)) {
            // Kepanitraan menggunakan input_1, input_2, label_input_2, tipe_input
            $query->select([
                'id', 'sasaran_strategis', 'indikator_kinerja', 'target', 
                'label_input_1', 'label_input_2', 'input_1', 'input_2', 
                'realisasi', 'capaian', 'status_capaian',
                'hambatan', 'rekomendasi', 'tindak_lanjut', 'keberhasilan',
                'bulan', 'tahun', 'created_at', 'rumus', 'tipe_input'
            ]);
        } elseif (in_array($jenis, $jenisKesekretariatan)) {
            // Kesekretariatan hanya menggunakan input_1, tanpa input_2
            $query->select([
                'id', 'sasaran_strategis', 'indikator_kinerja', 'target', 
                'label_input_1', 'input_1',
                'capaian', 'status_capaian',
                'hambatan', 'rekomendasi', 'tindak_lanjut', 'keberhasilan',
                'bulan', 'tahun', 'created_at'
            ]);
        } else {
            // Fallback
            $query->select([
                'id', 'sasaran_strategis', 'indikator_kinerja', 'target', 
                'label_input_1', 'input_1',
                'capaian', 'status_capaian',
                'hambatan', 'rekomendasi', 'tindak_lanjut', 'keberhasilan',
                'bulan', 'tahun', 'created_at'
            ]);
        }

        // Filter berdasarkan periode
        if ($jenisLaporan == 'bulanan') {
            if ($bulan && $tahun) {
                $query->where('bulan', $bulan)
                      ->where('tahun', $tahun);
            } elseif ($tahun) {
                $query->where('tahun', $tahun);
            }
        } elseif ($jenisLaporan == 'tahunan') {
            if ($tahun) {
                $query->where('tahun', $tahun);
            }
        } elseif ($jenisLaporan == 'triwulan' && $triwulan && $tahun) {
            list($startMonth, $endMonth) = $this->getTriwulanMonths($triwulan);
            $query->where('tahun', $tahun)
                  ->whereBetween('bulan', [$startMonth, $endMonth]);
        }

        $data = $query->get();

        // Format data berdasarkan jenis
        return $data->map(function ($item) use ($jenis, $jenisKepanitraan, $jenisKesekretariatan) {
            $item->jenis = $jenis;
            $item->tanggal = $item->created_at ? Carbon::parse($item->created_at) : now();
            
            // Format data berdasarkan jenis
            if (in_array($jenis, $jenisKepanitraan)) {
                $item = $this->formatKepanitraanData($item);
            } elseif (in_array($jenis, $jenisKesekretariatan)) {
                $item = $this->formatKesekretariatanData($item);
            }
            
            // Pastikan nilai default untuk kolom analisis
            $item->hambatan = $item->hambatan ?? '-';
            $item->rekomendasi = $item->rekomendasi ?? '-';
            $item->tindak_lanjut = $item->tindak_lanjut ?? '-';
            $item->keberhasilan = $item->keberhasilan ?? '-';
            
            // Pastikan nilai default untuk input yang kosong
            $item->input_1 = $item->input_1 ?? 0;
            // Untuk kesekretariatan, tidak ada input_2
            if (in_array($jenis, $jenisKesekretariatan)) {
                $item->input_2 = null;
            } else {
                // Untuk kepanitraan, jangan ubah input_2 menjadi 0 jika null
                if (!isset($item->input_2)) {
                    $item->input_2 = null;
                }
            }
            $item->realisasi = $item->realisasi ?? 0;
            $item->capaian = $item->capaian ?? 0;
            $item->target = $item->target ?? 0;
            
            return $item;
        });
    }

    /**
     * Format data khusus untuk bagian kepanitraan
     */
    private function formatKepanitraanData($item)
    {
        // Tentukan apakah menggunakan input_2 berdasarkan tipe_input
        // Jika tipe_input = 2 atau ada nilai di input_2, maka gunakan input_2
        if (isset($item->tipe_input) && $item->tipe_input == 2) {
            $item->use_input_2 = true;
        } elseif (isset($item->tipe_input) && $item->tipe_input == 'dua_input') {
            $item->use_input_2 = true;
        } else {
            // Default: jika ada nilai di input_2 (tidak null), anggap menggunakan input_2
            $item->use_input_2 = ($item->input_2 !== null && $item->input_2 !== '');
        }
        
        // Pastikan realisasi ada
        if (empty($item->realisasi) && $item->realisasi !== 0) {
            // Jika tidak ada realisasi, hitung berdasarkan input
            // Ini hanya fallback, karena seharusnya realisasi sudah dihitung saat input data
            if ($item->use_input_2 && $item->input_2 > 0) {
                // Jika menggunakan 2 input, realisasi = input_1 / input_2 * 100
                $item->realisasi = $item->input_2 > 0 ? ($item->input_1 / $item->input_2) * 100 : 0;
            } else {
                // Jika 1 input, realisasi = input_1
                $item->realisasi = $item->input_1;
            }
        }
        
        // Hitung capaian jika belum ada
        if (empty($item->capaian) && $item->capaian !== 0 && $item->target > 0) {
            $item->capaian = ($item->realisasi / $item->target) * 100;
            
            // Tentukan status capaian
            if ($item->capaian >= 90) {
                $item->status_capaian = 'Sangat Baik';
            } elseif ($item->capaian >= 70) {
                $item->status_capaian = 'Baik';
            } elseif ($item->capaian > 0) {
                $item->status_capaian = 'Cukup';
            } else {
                $item->status_capaian = 'Belum Tercapai';
            }
        }
        
        return $item;
    }

    /**
     * Format data khusus untuk bagian kesekretariatan
     */
    private function formatKesekretariatanData($item)
    {
        // Kesekretariatan hanya menggunakan 1 input
        $item->use_input_2 = false;
        
        // Untuk kesekretariatan, realisasi = input_1
        if (empty($item->realisasi) && $item->realisasi !== 0) {
            $item->realisasi = $item->input_1 ?? 0;
        }
        
        // Hitung capaian jika belum ada
        if (empty($item->capaian) && $item->capaian !== 0 && $item->target > 0) {
            if ($item->target > 0) {
                $item->capaian = ($item->realisasi / $item->target) * 100;
            } else {
                $item->capaian = 0;
            }
            
            // Tentukan status capaian
            if ($item->capaian >= 90) {
                $item->status_capaian = 'Sangat Baik';
            } elseif ($item->capaian >= 70) {
                $item->status_capaian = 'Baik';
            } elseif ($item->capaian > 0) {
                $item->status_capaian = 'Cukup';
            } else {
                $item->status_capaian = 'Belum Tercapai';
            }
        }
        
        return $item;
    }

    private function getTriwulanMonths($triwulan)
    {
        switch ($triwulan) {
            case '1':
                return [1, 3];
            case '2':
                return [4, 6];
            case '3':
                return [7, 9];
            case '4':
                return [10, 12];
            default:
                return [1, 12];
        }
    }

    private function getModels()
    {
        return [
            'perdata' => Perdata::class,
            'pidana' => Pidana::class,
            'tipikor' => Tipikor::class,
            'phi' => PHI::class,
            'hukum' => Hukum::class,
            'ptip' => PTIP::class,
            'umum_keuangan' => UmumKeuangan::class,
            'kepegawaian' => Kepegawaian::class,
        ];
    }
    
    /**
     * Helper function untuk memformat angka
     */
    private function formatAngka($value)
    {
        if (is_numeric($value) && $value != 0) {
            return number_format($value, 0, ',', '.');
        }
        return '-';
    }
    
    /**
     * Helper function untuk memformat persentase
     */
    private function formatPersen($value)
    {
        if (is_numeric($value)) {
            return number_format($value, 1, ',', '.') . '%';
        }
        return '-';
    }
}