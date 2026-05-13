<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dosen_dashboard() {
        $user = Auth::user();
        $dosen = User::where('role', '=', 'dosen')->count();
        $pengajuanSaya = $user->getPengajuans()->count();
        $dalamProses = $user->getPengajuans()->where('status', '=', 'DALAM_PROSES')->count();
        return view('Dosen.dashboard', compact('dosen', 'pengajuanSaya', 'dalamProses'));
    }

    public function kepegawaian_dashboard() {
        $totalDosen = User::whereRole('dosen')->count();
        $totalPengajuan = Pengajuan::all()->count();
        $berkasDiterima = Pengajuan::whereTahap(['SIDANG_KOMITE','SIDANG_SENAT','PENGAJUAN_SISTER', 'SK_KENAIKAN'])->count();
        $berkasDitolak = Pengajuan::whereStatus('REVISI')->count();
        return view('Kepegawaian.dashboard', compact('totalDosen', 'totalPengajuan', 'berkasDiterima', 'berkasDitolak'));
    }

    public function comite_dashboard() {
        $totalDosen = User::whereRole('dosen')->count();
        $totalPengajuan = Pengajuan::all()->count();
        $berkasDiterima = Pengajuan::whereTahap(['SIDANG_KOMITE','SIDANG_SENAT','PENGAJUAN_SISTER', 'SK_KENAIKAN'])->count();
        $berkasDitolak = Pengajuan::whereStatus('REVISI')->count();
        return view('Comite.dashboard', compact('totalDosen', 'totalPengajuan', 'berkasDiterima', 'berkasDitolak'));
    }

    public function senat_dashboard() {
        $totalDosen = User::whereRole('dosen')->count();
        $totalPengajuan = Pengajuan::all()->count();
        $berkasDiterima = Pengajuan::whereTahap(['SIDANG_KOMITE','SIDANG_SENAT','PENGAJUAN_SISTER', 'SK_KENAIKAN'])->count();
        $berkasDitolak = Pengajuan::whereStatus('REVISI')->count();
        return view('Senat.dashboard', compact('totalDosen', 'totalPengajuan', 'berkasDiterima', 'berkasDitolak'));
    }

    public function superadmin_dashboard() {
        return view('Superadmin.dashboard');
    }
}
