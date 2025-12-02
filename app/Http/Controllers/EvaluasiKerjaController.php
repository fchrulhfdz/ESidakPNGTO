<?php

namespace App\Http\Controllers;

use App\Models\EvaluasiKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EvaluasiKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Query dasar
        $query = EvaluasiKerja::query();
        
        if ($user->isSuperAdmin()) {
            // Super admin bisa melihat semua
            if ($request->has('bagian') && $request->bagian) {
                $query->where('bagian', $request->bagian);
            }
        } else {
            // Admin biasa hanya bisa melihat bagiannya
            $query->where('bagian', $user->role);
        }
        
        $evaluasiKerja = $query->orderBy('created_at', 'desc')->paginate(10);

        $bagianMapping = [
            'perdata' => 'Perdata',
            'pidana' => 'Pidana', 
            'tipikor' => 'Tipikor',
            'phi' => 'PHI',
            'hukum' => 'Hukum',
            'ptip' => 'PTIP',
            'kepegawaian' => 'Kepegawaian',
            'umum_keuangan' => 'Umum & Keuangan',
            'super_admin' => 'Super Admin'
        ];

        return view('evaluasi-kerja.index', compact('evaluasiKerja', 'bagianMapping'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Aturan validasi dasar
        $rules = [
            'judul' => 'required|string|max:255',
            'tahun' => 'required|digits:4',
            'bulan' => 'required|string',
            'file' => 'required|file|mimes:doc,docx|max:5120', // Max 5MB
            'keterangan' => 'nullable|string|max:500',
        ];
        
        // Jika super admin, validasi bagian harus ada
        if ($user->isSuperAdmin()) {
            $rules['bagian'] = 'required|string|in:perdata,pidana,tipikor,phi,hukum,ptip,kepegawaian,umum_keuangan';
            $bagian = $request->bagian;
        } else {
            // Untuk admin biasa, bagian dari role user
            $rules['bagian'] = 'required|string';
            $bagian = $user->role;
            
            // Pastikan admin hanya bisa upload untuk bagiannya sendiri
            if ($request->bagian !== $user->role) {
                return redirect()->back()
                    ->with('error', 'Anda hanya dapat mengupload file untuk bagian Anda sendiri.')
                    ->withInput();
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $file = $request->file('file');
        
        // Generate unique filename
        $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $path = $file->storeAs('evaluasi-kerja', $filename, 'public');

        EvaluasiKerja::create([
            'bagian' => $bagian,
            'nama_file' => $file->getClientOriginalName(),
            'path_file' => $path,
            'judul' => $request->judul,
            'tahun' => $request->tahun,
            'bulan' => $request->bulan,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('evaluasi-kerja.index')
            ->with('success', 'File evaluasi kerja berhasil diupload.');
    }

    /**
     * Download file
     */
    public function download($id)
    {
        $evaluasi = EvaluasiKerja::findOrFail($id);
        $user = Auth::user();

        // Cek hak akses
        if (!$user->isSuperAdmin() && $evaluasi->bagian !== $user->role) {
            abort(403, 'Anda tidak memiliki akses untuk mendownload file ini.');
        }

        return Storage::disk('public')->download($evaluasi->path_file, $evaluasi->nama_file);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluasi = EvaluasiKerja::findOrFail($id);
        $user = Auth::user();

        // Cek hak akses: 
        // 1. Super admin bisa hapus semua
        // 2. Admin biasa hanya bisa hapus file bagiannya sendiri
        if (!$user->isSuperAdmin() && $evaluasi->bagian !== $user->role) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus file ini.');
        }

        // Hapus file dari storage
        Storage::disk('public')->delete($evaluasi->path_file);
        
        // Hapus record dari database
        $evaluasi->delete();

        return redirect()->route('evaluasi-kerja.index')
            ->with('success', 'File evaluasi kerja berhasil dihapus.');
    }
}