<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\ProgresPengajuan;
use App\Models\ReviewPengajuan;
use Auth;
use Illuminate\Http\Request;

class ReviewPengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function action_review(Request $request, $pengajuanId)
{
    try {

        $pengajuan   = Pengajuan::find($pengajuanId);
        $formDetails = $pengajuan->getFormPengajuan->getFormPengajuanDetails;
        $user        = Auth::user();
        $approveCount = 0;

$lastVersion = ReviewPengajuan::where('pengajuan_id', $pengajuanId)
                    ->max('version') ?? 0;
$nextVersion = $lastVersion + 1;

        foreach ($formDetails as $detail) {
            $key    = $detail->key;
            $status = $request->input($key, 'revisi');

            if ($status === 'approve') {
                $approveCount++;
            }

            ReviewPengajuan::create([
                'pengajuan_id'  => $pengajuanId,
                'verified_by'   => $user->id,
                'key'           => $key,
                'status'        => $status,
                'reviewer_type' => 'kepegawaian',
                'keterangan'    => $request->input("{$key}-keterangan"),
                'version'       => $nextVersion,
            ]);
        }

        if ($approveCount == $formDetails->count()) {
            $pengajuan->status = 'DALAM_PROSES';
            $pengajuan->tahap  = 'SIDANG_KOMITE';
            $keteranganProgres = 'Semua berkas diverifikasi, lanjut ke sidang komite';
            $statusProgres     = 'DISETUJUI';
        } else {
            $pengajuan->status = 'REVISI';
            $pengajuan->tahap  = 'PERLU_DILENGKAPI';
            $keteranganProgres = 'Ada berkas yang perlu diperbaiki oleh dosen';
            $statusProgres     = 'REVISI';
        }

        ProgresPengajuan::create([
            'pengajuan_id' => $pengajuanId,
            'verified_by'  => $user->id,
            'status'       => $statusProgres,
            'tahap'        => 'VERIFIKASI_BERKAS',
            'keterangan'   => $keteranganProgres,
        ]);

        $pengajuan->save();

        return redirect()->route('kepegawaian.pengajuan.list')
                         ->with('success', 'Review berhasil disimpan');

    } catch (\Throwable $th) {
        return redirect()->back()->with('error', $th->getMessage());
    }
}

    /**
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
    public function show(ReviewPengajuan $reviewPengajuan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReviewPengajuan $reviewPengajuan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReviewPengajuan $reviewPengajuan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReviewPengajuan $reviewPengajuan)
    {
        //
    }
}
