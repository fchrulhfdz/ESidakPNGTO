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

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $data = collect();
        $jenisLaporan = $request->jenis_laporan ?? 'bulanan'; // bulanan, tahunan, triwulan
        
        if ($request->has('search')) {
            $bulan = $request->bulan;
            $tahun = $request->tahun;
            $bagian = $request->bagian;
            $triwulan = $request->triwulan;

            // Ambil data berdasarkan jenis laporan
            $data = $this->getFilteredData($bulan, $tahun, $bagian, $jenisLaporan, $triwulan);
        }

        return view('laporan.index', compact('data', 'jenisLaporan'));
    }

    public function cetakWord(Request $request)
    {
        $jenisLaporan = $request->jenis_laporan ?? 'bulanan';
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $bagian = $request->bagian;
        $triwulan = $request->triwulan;

        $data = $this->getFilteredData($bulan, $tahun, $bagian, $jenisLaporan, $triwulan);
        $user = Auth::user();
        
        // Bagian mapping untuk display
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
        
        // Periode text
        $periodeText = "Periode: ";
        if ($jenisLaporan == 'bulanan' && $bulan && $tahun) {
            $periodeText .= $namaBulan[$bulan] . " " . $tahun;
        } elseif ($jenisLaporan == 'tahunan' && $tahun) {
            $periodeText .= "Tahun " . $tahun;
        } elseif ($jenisLaporan == 'triwulan' && $triwulan && $tahun) {
            $triwulanText = match($triwulan) {
                '1' => 'I (Januari - Maret)',
                '2' => 'II (April - Juni)',
                '3' => 'III (Juli - September)',
                '4' => 'IV (Oktober - Desember)',
                default => $triwulan
            };
            $periodeText .= "Triwulan " . $triwulanText . " Tahun " . $tahun;
        }
        
        $filename = 'laporan-e-sidak-' . date('Y-m-d-His') . '.doc';
        
        // Return view khusus untuk Word
        return response()
            ->view('laporan.word-template', compact('data', 'periodeText', 'user', 'bagianMapping', 'jenisLaporan'))
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

        return $data->sortBy('created_at');
    }

    private function queryData($modelClass, $bulan, $tahun, $jenis, $jenisLaporan, $triwulan = null)
    {
        $query = $modelClass::query();

        if ($jenisLaporan == 'bulanan') {
            if ($bulan && $tahun) {
                $query->whereMonth('created_at', $bulan)
                      ->whereYear('created_at', $tahun);
            } elseif ($tahun) {
                $query->whereYear('created_at', $tahun);
            }
        } elseif ($jenisLaporan == 'tahunan') {
            if ($tahun) {
                $query->whereYear('created_at', $tahun);
            }
        } elseif ($jenisLaporan == 'triwulan' && $triwulan && $tahun) {
            list($startMonth, $endMonth) = $this->getTriwulanMonths($triwulan);
            $query->whereYear('created_at', $tahun)
                  ->whereMonth('created_at', '>=', $startMonth)
                  ->whereMonth('created_at', '<=', $endMonth);
        }

        return $query->get()
            ->map(function ($item) use ($jenis) {
                $item->jenis = $jenis;
                $item->tanggal = $item->created_at;
                return $item;
            });
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
}