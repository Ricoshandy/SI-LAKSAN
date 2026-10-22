<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
  
public function dosen_dashboard(Request $request)
{
    $user = Auth::user();

    // =========================================
    // 1. STATISTIK LAMA - TETAP DIPERTAHANKAN
    // =========================================

    $totalDosen = User::whereRole('dosen')->count();

    $dosen = User::where('role', 'dosen')->count();

    $pengajuanSaya = $user->getPengajuans()->count();

    $dalamProses = $user->getPengajuans()
        ->where('status', 'DALAM_PROSES')
        ->count();


    // =========================================
    // 2. VALIDASI DAN FILTER TAHUN
    // =========================================

    $request->validate([
        'tahun' => ['nullable', 'integer', 'between:2000,2100'],
    ]);

    $daftarTahun = DB::table('periodes')
        ->selectRaw('YEAR(date_start) AS tahun')
        ->whereNotNull('date_start')
        ->distinct()
        ->orderByDesc('tahun')
        ->pluck('tahun');

    $tahunDipilih = (int) (
        $request->query('tahun')
        ?? $daftarTahun->first()
        ?? now()->year
    );


    // =========================================
    // 3. STATISTIK PENGAJUAN MILIK DOSEN LOGIN
    // =========================================

    $statistikPeriode = DB::table('periodes as p')
      
->leftJoin('pengajuans as a', 'a.periode_id', '=', 'p.id')

        ->whereYear('p.date_start', $tahunDipilih)
        ->select(
            'p.id',
            'p.name',
            'p.date_start',
            'p.date_end'
        )
        ->selectRaw('COUNT(a.id) AS jumlah_pengajuan')
        ->groupBy(
            'p.id',
            'p.name',
            'p.date_start',
            'p.date_end'
        )
        ->orderBy('p.date_start')
        ->get();


    // =========================================
    // 4. DATA UNTUK CHART.JS
    // =========================================

    $periodeLabels = $statistikPeriode
        ->pluck('name')
        ->values()
        ->all();

    $jumlahPengajuan = $statistikPeriode
        ->pluck('jumlah_pengajuan')
        ->map(fn ($jumlah) => (int) $jumlah)
        ->values()
        ->all();


    // =========================================
    // 5. RESPONSE UNTUK REFRESH OTOMATIS
    // =========================================

    if ($request->query('statistik') === '1') {

        return response()->json([
            'labels' => $periodeLabels,
            'jumlah' => $jumlahPengajuan,
            'updated_at' => now()->format('H:i:s'),
        ])->header('Cache-Control', 'no-store');

    }


    // =========================================
    // 6. KIRIM DATA KE DASHBOARD DOSEN
    // =========================================

    return view('Dosen.dashboard', compact(
        'dosen',
        'pengajuanSaya',
        'dalamProses',
        'totalDosen',
        'daftarTahun',
        'tahunDipilih',
        'periodeLabels',
        'jumlahPengajuan'
    ));
}


public function kepegawaian_dashboard(Request $request)
{
    // Statistik lama - tetap dipertahankan
    $totalDosen = User::where('role', 'dosen')->count();

    $totalPengajuan = Pengajuan::count();

    $berkasDiterima = Pengajuan::whereIn('tahap', [
        'SIDANG_KOMITE',
        'SIDANG_SENAT',
        'PENGAJUAN_SISTER',
        'SK_KENAIKAN',
    ])->count();

    $berkasDitolak = Pengajuan::where('status', 'REVISI')->count();

    // Validasi filter tahun
    $request->validate([
        'tahun' => ['nullable', 'integer', 'between:2000,2100'],
    ]);

    // Ambil daftar tahun dari database
    $daftarTahun = DB::table('periodes')
        ->selectRaw('YEAR(date_start) AS tahun')
        ->whereNotNull('date_start')
        ->distinct()
        ->orderByDesc('tahun')
        ->pluck('tahun');

    // Pilih tahun terbaru jika belum ada filter
    $tahunDipilih = (int) (
        $request->query('tahun')
        ?? $daftarTahun->first()
        ?? now()->year
    );

    // Ambil statistik pengajuan berdasarkan periode
    $statistikPeriode = DB::table('periodes as p')
        ->leftJoin(
            'pengajuans as a',
            'a.periode_id',
            '=',
            'p.id'
        )
        ->whereYear('p.date_start', $tahunDipilih)
        ->select(
            'p.id',
            'p.name',
            'p.date_start',
            'p.date_end'
        )
        ->selectRaw('COUNT(a.id) AS jumlah_pengajuan')
        ->groupBy(
            'p.id',
            'p.name',
            'p.date_start',
            'p.date_end'
        )
        ->orderBy('p.date_start')
        ->get();

    // Sesuaikan dengan variabel Chart.js lama
    $periodeLabels = $statistikPeriode
        ->pluck('name')
        ->values()
        ->all();

    $jumlahPengajuan = $statistikPeriode
        ->pluck('jumlah_pengajuan')
        ->map(fn ($jumlah) => (int) $jumlah)
        ->values()
        ->all();

    // Respons JSON untuk refresh grafik otomatis
    // Menggunakan URL dashboard yang sama, tanpa route baru.
    if ($request->query('statistik') === '1') {
        return response()->json([
            'labels' => $periodeLabels,
            'jumlah' => $jumlahPengajuan,
            'updated_at' => now()->format('H:i:s'),
        ])->header('Cache-Control', 'no-store');
    }

    // Tampilan dashboard seperti sebelumnya
    return view('Kepegawaian.dashboard', compact(
        'totalDosen',
        'totalPengajuan',
        'berkasDiterima',
        'berkasDitolak',
        'daftarTahun',
        'tahunDipilih',
        'periodeLabels',
        'jumlahPengajuan'
    ));
}


public function comite_dashboard(Request $request)
{
    // =========================================
    // 1. STATISTIK DASHBOARD LAMA
    // =========================================

    $totalDosen = User::where('role', 'dosen')->count();

    $totalPengajuan = Pengajuan::count();

    $berkasDiterima = Pengajuan::whereIn('tahap', [
        'SIDANG_KOMITE',
        'SIDANG_SENAT',
        'PENGAJUAN_SISTER',
        'SK_KENAIKAN',
    ])->count();

    $berkasDitolak = Pengajuan::where('status', 'REVISI')->count();


    // =========================================
    // 2. FILTER TAHUN
    // =========================================

    $request->validate([
        'tahun' => ['nullable', 'integer', 'between:2000,2100'],
    ]);

    $daftarTahun = DB::table('periodes')
        ->selectRaw('YEAR(date_start) AS tahun')
        ->whereNotNull('date_start')
        ->distinct()
        ->orderByDesc('tahun')
        ->pluck('tahun');

    $tahunDipilih = (int) (
        $request->query('tahun')
        ?? $daftarTahun->first()
        ?? now()->year
    );


    // =========================================
    // 3. STATISTIK SELURUH PENGAJUAN PER PERIODE
    // =========================================

    $statistikPeriode = DB::table('periodes as p')
        ->leftJoin(
            'pengajuans as a',
            'a.periode_id',
            '=',
            'p.id'
        )
        ->whereYear('p.date_start', $tahunDipilih)
        ->select(
            'p.id',
            'p.name',
            'p.date_start',
            'p.date_end'
        )
        ->selectRaw('COUNT(a.id) AS jumlah_pengajuan')
        ->groupBy(
            'p.id',
            'p.name',
            'p.date_start',
            'p.date_end'
        )
        ->orderBy('p.date_start')
        ->get();


    // =========================================
    // 4. DATA GRAFIK
    // =========================================

    $periodeLabels = $statistikPeriode
        ->pluck('name')
        ->values()
        ->all();

    $jumlahPengajuan = $statistikPeriode
        ->pluck('jumlah_pengajuan')
        ->map(fn ($jumlah) => (int) $jumlah)
        ->values()
        ->all();


    // =========================================
    // 5. REFRESH OTOMATIS VIA JSON
    // =========================================

    if ($request->query('statistik') === '1') {
        return response()->json([
            'labels' => $periodeLabels,
            'jumlah' => $jumlahPengajuan,
            'updated_at' => now()->format('H:i:s'),
        ])->header('Cache-Control', 'no-store');
    }


    // =========================================
    // 6. TAMPILKAN DASHBOARD KOMITE
    // =========================================

    return view('Comite.dashboard', compact(
        'totalDosen',
        'totalPengajuan',
        'berkasDiterima',
        'berkasDitolak',
        'daftarTahun',
        'tahunDipilih',
        'periodeLabels',
        'jumlahPengajuan'
    ));
}


 
public function senat_dashboard(Request $request)
{
    // ========================================
    // 1. STATISTIK DASHBOARD LAMA
    // ========================================

    $totalDosen = User::where('role', 'dosen')->count();

    $totalPengajuan = Pengajuan::count();

    $berkasDiterima = Pengajuan::whereIn('tahap', [
        'SIDANG_KOMITE',
        'SIDANG_SENAT',
        'PENGAJUAN_SISTER',
        'SK_KENAIKAN',
    ])->count();

    $berkasDitolak = Pengajuan::where('status', 'REVISI')->count();


    // ========================================
    // 2. FILTER TAHUN
    // ========================================

    $request->validate([
        'tahun' => ['nullable', 'integer', 'between:2000,2100'],
    ]);

    $daftarTahun = DB::table('periodes')
        ->selectRaw('YEAR(date_start) AS tahun')
        ->whereNotNull('date_start')
        ->distinct()
        ->orderByDesc('tahun')
        ->pluck('tahun');

    $tahunDipilih = (int) (
        $request->query('tahun')
        ?? $daftarTahun->first()
        ?? now()->year
    );


    // ========================================
    // 3. STATISTIK SELURUH PENGAJUAN
    // ========================================

    $statistikPeriode = DB::table('periodes as p')
        ->leftJoin(
            'pengajuans as a',
            'a.periode_id',
            '=',
            'p.id'
        )
        ->whereYear('p.date_start', $tahunDipilih)
        ->select(
            'p.id',
            'p.name',
            'p.date_start',
            'p.date_end'
        )
        ->selectRaw('COUNT(a.id) AS jumlah_pengajuan')
        ->groupBy(
            'p.id',
            'p.name',
            'p.date_start',
            'p.date_end'
        )
        ->orderBy('p.date_start')
        ->get();


    // ========================================
    // 4. DATA CHART.JS
    // ========================================

    $periodeLabels = $statistikPeriode
        ->pluck('name')
        ->values()
        ->all();

    $jumlahPengajuan = $statistikPeriode
        ->pluck('jumlah_pengajuan')
        ->map(fn ($jumlah) => (int) $jumlah)
        ->values()
        ->all();


    // ========================================
    // 5. RESPONSE REFRESH OTOMATIS
    // ========================================

    if ($request->query('statistik') === '1') {
        return response()->json([
            'labels' => $periodeLabels,
            'jumlah' => $jumlahPengajuan,
            'updated_at' => now()->format('H:i:s'),
        ])->header('Cache-Control', 'no-store');
    }


    // ========================================
    // 6. KIRIM DATA KE DASHBOARD SENAT
    // ========================================

    return view('Senat.dashboard', compact(
        'totalDosen',
        'totalPengajuan',
        'berkasDiterima',
        'berkasDitolak',
        'daftarTahun',
        'tahunDipilih',
        'periodeLabels',
        'jumlahPengajuan'
    ));
}

    public function superadmin_dashboard() {
        return view('Superadmin.dashboard');
    }
}
