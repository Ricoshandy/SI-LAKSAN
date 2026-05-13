<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\ProgresPengajuan;
use App\Models\SidangPengajuan;
use Auth;
use Illuminate\Http\Request;
use Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SidangPengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function action_sidang_komite(Request $request, $pengajuanId)
    {
        $pengajuan = Pengajuan::find($pengajuanId);
        $userDosen = $pengajuan->getUser;

        $sidangBifore = SidangPengajuan::where('pengajuan_id', '=', $pengajuanId)->orderBy('created_at', 'DESC')->first();

        $file = $request->file('berita_acara');
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $destinationPath = "sidang/{$userDosen->email}";
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }
        Storage::putFileAs($destinationPath, $file, $fileName);
        $fullPath = $destinationPath . '/' . $fileName;

        $sidang = new SidangPengajuan();

        $sidang->pengajuan_id = $pengajuanId;
        $sidang->verified_by = Auth::user()->id;
        $sidang->berita_acara = $fullPath;
        $sidang->sidang = $request->input('sidang');
        $sidang->status = $request->input('status');
        $sidang->keterangan = "Hasil Sidang dinyatakan {$request->input('status')}";
        $sidang->version = empty($sidangBifore) ? 1 : $sidangBifore->version++;

        $sidang->save();

        if ($sidang->status == 'LULUS') {
            $pengajuan->status = 'DALAM_PROSES';
            $pengajuan->tahap = 'SIDANG_SENAT';
            $status = 'DISETUJUI';

        } else {
            $pengajuan->status = 'DITOLAK';
            $pengajuan->tahap = 'SIDANG_KOMITE';
            $status = 'DITOLAK';
        }

        
        ProgresPengajuan::create([
            'pengajuan_id' => $pengajuanId,
            'verified_by' => Auth::user()->id,
            'status' => $status,
            'tahap' => 'SIDANG_KOMITE',
            'keterangan' => $sidang->keterangan,
        ]);
        
        $pengajuan->save();

        return redirect()->route('comite_dashboard')->with('success', 'Berkas Sidang Berhasil di Submit');

    }

    public function action_sidang_senat(Request $request, $pengajuanId)
    {
        $pengajuan = Pengajuan::find($pengajuanId);
        $userDosen = $pengajuan->getUser;

        $sidangBifore = SidangPengajuan::where('pengajuan_id', '=', $pengajuanId)->orderBy('created_at', 'DESC')->first();

        $file = $request->file('berita_acara');
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $destinationPath = "sidang/{$userDosen->email}";
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }
        Storage::putFileAs($destinationPath, $file, $fileName);
        $fullPath = $destinationPath . '/' . $fileName;

        $sidang = new SidangPengajuan();

        $sidang->pengajuan_id = $pengajuanId;
        $sidang->verified_by = Auth::user()->id;
        $sidang->berita_acara = $fullPath;
        $sidang->sidang = $request->input('sidang');
        $sidang->status = $request->input('status');
        $sidang->keterangan = "Hasil Sidang dinyatakan {$request->input('status')}";
        $sidang->version = empty($sidangBifore) ? 1 : $sidangBifore->version++;

        $sidang->save();

        if ($sidang->status == 'LULUS') {
            $pengajuan->status = 'DALAM_PROSES';
            if ($pengajuan->getFormPengajuan->usul == 'ASISTEN_AHLI' || $pengajuan->getFormPengajuan->usul == 'LEKTOR') {
                $pengajuan->tahap = 'PENERBITAN_SK';
            } else {
                $pengajuan->tahap = 'PENGAJUAN_SISTER';
            }
            $status = 'DISETUJUI';

        } else {
            $pengajuan->status = 'DITOLAK';
            $pengajuan->tahap = 'SIDANG_SENAT';
            $status = 'DITOLAK';
        }

        
        ProgresPengajuan::create([
            'pengajuan_id' => $pengajuanId,
            'verified_by' => Auth::user()->id,
            'status' => $status,
            'tahap' => 'SIDANG_SENAT',
            'keterangan' => $sidang->keterangan,
        ]);
        
        $pengajuan->save();

        return redirect()->route('senat_dashboard')->with('success', 'Berkas Sidang Berhasil di Submit');

    }
    /**
     * 
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SidangPengajuan $sidangPengajuan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SidangPengajuan $sidangPengajuan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SidangPengajuan $sidangPengajuan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SidangPengajuan $sidangPengajuan)
    {
        //
    }
}
