<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Support\Facades\Http;
use Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $apiUrl;
    private $apiKey;
    private $tokenPath;

    public function __construct()
    {
        $this->apiUrl    = env('AMPERA_API_URL');
        $this->apiKey    = env('AMPERA_API_KEY');
        $this->tokenPath = storage_path('app/tokenAmpera.json');
    }

    private function getToken(): string
    {
        if (file_exists($this->tokenPath)) {
            $tokenData = json_decode(file_get_contents($this->tokenPath), true);
            if (!empty($tokenData['token']) && $tokenData['expired_in'] > time()) {
                return $tokenData['token'];
            }
        }

        $response = Http::withHeaders([
            'User-Agent' => 'PostmanRuntime/7.32.3',
            'Accept'     => 'application/json',
        ])->asForm()->post($this->apiUrl . '/auth/gettoken', [
            'api_key' => $this->apiKey,
        ]);

        $data = $response->json();

        if (empty($data['token'])) {
            throw new \Exception('Gagal ambil token API Ampera: ' . $response->body());
        }

        $tokenData = [
            'token'      => $data['token'],
            'expired_in' => $data['expired_in'],
        ];

        file_put_contents($this->tokenPath, json_encode($tokenData));

        return $tokenData['token'];
    }

    private function getDosenFromAPI(): \Illuminate\Support\Collection
    {
        $allDosen = collect();
        $page     = 1;

        do {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getToken(),
                'Accept'        => 'application/json',
                'User-Agent'    => 'PostmanRuntime/7.32.3',
            ])->post("{$this->apiUrl}/api/pegawai/filter/{$page}", [
                'nm'                          => '',
                'nip'                         => '',
                'default_id_jenis_ketenagaan' => '1',
                'id_status_aktif_pegawai'     => 1,
                'limit'                       => 100,
            ]);

            if (!$response->successful()) break;

            $data = $response->json()['data'] ?? [];
            if (empty($data)) break;

            $allDosen = $allDosen->merge($data);
            $page++;

        } while (true);

        return $allDosen;
    }

    public function dosen_dashboard()
    {
        $user          = Auth::user();
        $pengajuanSaya = $user->getPengajuans()->count();
        $dalamProses   = $user->getPengajuans()->where('status', 'DALAM_PROSES')->count();
        $totalDosen    = $this->getDosenFromAPI()->count();

        return view('Dosen.dashboard', compact('totalDosen', 'pengajuanSaya', 'dalamProses'));
    }

    public function kepegawaian_dashboard()
    {
        $totalDosen     = $this->getDosenFromAPI()->count();
        $totalPengajuan = Pengajuan::count();
        $berkasDiterima = Pengajuan::whereIn('tahap', ['SIDANG_KOMITE', 'SIDANG_SENAT', 'PENGAJUAN_SISTER', 'SK_KENAIKAN'])->count();
        $berkasDitolak  = Pengajuan::where('status', 'REVISI')->count();

        return view('Kepegawaian.dashboard', compact('totalDosen', 'totalPengajuan', 'berkasDiterima', 'berkasDitolak'));
    }

    public function comite_dashboard()
    {
        $totalDosen     = $this->getDosenFromAPI()->count();
        $totalPengajuan = Pengajuan::count();
        $berkasDiterima = Pengajuan::whereIn('tahap', ['SIDANG_KOMITE', 'SIDANG_SENAT', 'PENGAJUAN_SISTER', 'SK_KENAIKAN'])->count();
        $berkasDitolak  = Pengajuan::where('status', 'REVISI')->count();

        return view('Comite.dashboard', compact('totalDosen', 'totalPengajuan', 'berkasDiterima', 'berkasDitolak'));
    }

    public function senat_dashboard()
    {
        $totalDosen     = $this->getDosenFromAPI()->count();
        $totalPengajuan = Pengajuan::count();
        $berkasDiterima = Pengajuan::whereIn('tahap', ['SIDANG_KOMITE', 'SIDANG_SENAT', 'PENGAJUAN_SISTER', 'SK_KENAIKAN'])->count();
        $berkasDitolak  = Pengajuan::where('status', 'REVISI')->count();

        return view('Senat.dashboard', compact('totalDosen', 'totalPengajuan', 'berkasDiterima', 'berkasDitolak'));
    }

    public function superadmin_dashboard()
    {
        return view('Superadmin.dashboard');
    }
}