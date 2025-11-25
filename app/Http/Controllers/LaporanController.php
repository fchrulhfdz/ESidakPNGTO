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
use PDF;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $data = collect();
        
        if ($request->has('search')) {
            $bulan = $request->bulan;
            $tahun = $request->tahun;
            $bagian = $request->bagian;

            // Ambil data berdasarkan filter dan role user
            $data = $this->getFilteredData($bulan, $tahun, $bagian);
        }

        return view('laporan.index', compact('data'));
    }

    public function cetakPdf(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $bagian = $request->bagian;

        $data = $this->getFilteredData($bulan, $tahun, $bagian);

        $pdf = PDF::loadView('laporan.pdf', compact('data', 'bulan', 'tahun', 'bagian'))
                  ->setPaper('a4', 'landscape');
        
        return $pdf->download('laporan-e-sidak-' . date('Y-m-d-His') . '.pdf');
    }

    private function getFilteredData($bulan, $tahun, $bagian)
    {
        $data = collect();

        // Mapping model untuk setiap bagian
        $models = [
            'perdata' => Perdata::class,
            'pidana' => Pidana::class,
            'tipikor' => Tipikor::class,
            'phi' => PHI::class,
            'hukum' => Hukum::class,
            'ptip' => PTIP::class,
            'umum_keuangan' => UmumKeuangan::class,
            'kepegawaian' => Kepegawaian::class,
        ];

        $user = Auth::user();

        // Jika user adalah super admin, maka bisa memilih semua bagian
        if ($user->role === 'super_admin') {
            // Jika memilih semua bagian
            if ($bagian === 'all') {
                foreach ($models as $jenis => $modelClass) {
                    $modelData = $this->queryData($modelClass, $bulan, $tahun, $jenis);
                    $data = $data->merge($modelData);
                }
            } 
            // Jika memilih bagian tertentu
            else if (array_key_exists($bagian, $models)) {
                $modelData = $this->queryData($models[$bagian], $bulan, $tahun, $bagian);
                $data = $data->merge($modelData);
            }
        } 
        // Jika user bukan super admin, maka hanya menampilkan bagian sesuai role
        else {
            // Mapping role user ke bagian yang sesuai
            $roleToBagian = [
                'perdata' => 'perdata',
                'pidana' => 'pidana',
                'tipikor' => 'tipikor',
                'phi' => 'phi',
                'hukum' => 'hukum',
                'ptip' => 'ptip',
                'umum_keuangan' => 'umum_keuangan',
                'kepegawaian' => 'kepegawaian',
            ];

            if (array_key_exists($user->role, $roleToBagian)) {
                $bagianUser = $roleToBagian[$user->role];
                $modelClass = $models[$bagianUser];
                $modelData = $this->queryData($modelClass, $bulan, $tahun, $bagianUser);
                $data = $data->merge($modelData);
            }
        }

        return $data;
    }

    private function queryData($modelClass, $bulan, $tahun, $jenis)
    {
        return $modelClass::when($bulan, function($query, $bulan) {
                return $query->whereMonth('created_at', $bulan);
            })
            ->when($tahun, function($query, $tahun) {
                return $query->whereYear('created_at', $tahun);
            })
            ->get()
            ->map(function ($item) use ($jenis) {
                // Tambahkan property jenis untuk identifikasi
                $item->jenis = $jenis;
                $item->tanggal = $item->created_at;
                return $item;
            });
    }
}