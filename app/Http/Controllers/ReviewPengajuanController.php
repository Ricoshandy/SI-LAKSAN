<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\ReviewPengajuan;
use App\Models\ProgresPengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReviewPengajuanController extends Controller
{

    

public function saveDraft(Request $request, $pengajuanId)
{
    // Validasi data dari AJAX.
    $validated = $request->validate([
        'key' => ['required', 'string', 'max:255'],
        'status' => ['required', 'in:approve,revisi,ditolak'],
        'keterangan' => ['nullable', 'string', 'max:2000'],
    ]);

    return DB::transaction(function () use ($pengajuanId, $validated) {

        // Kunci pengajuan selama penyimpanan draft.
        // Mencegah bentrok dengan request AJAX lain
        // maupun proses Submit Review Berkas.
        $pengajuan = Pengajuan::with(
            'getFormPengajuan.getFormPengajuanDetails'
        )
            ->lockForUpdate()
            ->findOrFail($pengajuanId);

        // Draft hanya boleh disimpan saat verifikasi.
        abort_unless(
            $pengajuan->tahap === 'VERIFIKASI_BERKAS',
            422,
            'Pengajuan tidak berada pada tahap verifikasi berkas.'
        );

        // Pastikan key dokumen benar-benar milik
        // formulir pengajuan yang sedang diperiksa.
        $formPengajuan = $pengajuan->getFormPengajuan;

        abort_unless(
            $formPengajuan !== null,
            422,
            'Formulir pengajuan tidak ditemukan.'
        );

        $formDetails = $formPengajuan->getFormPengajuanDetails;

        $detailValid = $formDetails->contains(
            'key',
            $validated['key']
        );

        abort_unless(
            $detailValid,
            422,
            'Dokumen tidak ditemukan pada pengajuan ini.'
        );

        // Cari versi draft Kepegawaian yang masih aktif.
        $draftVersion = ReviewPengajuan::where(
            'pengajuan_id',
            $pengajuanId
        )
            ->where('reviewer_type', 'kepegawaian')
            ->where('is_submitted', false)
            ->max('version');

        // Bila belum ada draft, buat versi baru
        // setelah versi review terakhir.
        if ($draftVersion === null) {
            $lastVersion = ReviewPengajuan::where(
                'pengajuan_id',
                $pengajuanId
            )->max('version');

            $draftVersion = ($lastVersion ?? 0) + 1;
        }

        // Simpan atau perbarui review DRAFT saja.
        // Review final (is_submitted = true)
        // tidak akan ikut diperbarui.
        $review = ReviewPengajuan::updateOrCreate(
            [
                'pengajuan_id' => $pengajuanId,
                'key' => $validated['key'],
                'reviewer_type' => 'kepegawaian',
                'version' => $draftVersion,
                'is_submitted' => false,
            ],
            [
                'verified_by' => Auth::id(),
                'status' => $validated['status'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Action dokumen berhasil disimpan.',
            'data' => [
                'key' => $review->key,
                'status' => $review->status,
                'keterangan' => $review->keterangan,
                'version' => $review->version,
                'is_submitted' => $review->is_submitted,
            ],
        ]);
    });
}

    /**
     * Display a listing of the resource.
     */
  
public function action_review(Request $request, $pengajuanId)
{
    $pengajuan = Pengajuan::with(
        'getFormPengajuan.getFormPengajuanDetails'
    )->findOrFail($pengajuanId);

    abort_unless(
        $pengajuan->tahap === 'VERIFIKASI_BERKAS',
        422,
        'Pengajuan tidak berada pada tahap verifikasi berkas.'
    );


    // Finalisasi menggunakan draft yang sudah tersimpan.
    $result = DB::transaction(function () use (
        $request,
        $pengajuanId
    ) {
        $pengajuan = Pengajuan::with(
            'getFormPengajuan.getFormPengajuanDetails'
        )
            ->lockForUpdate()
            ->findOrFail($pengajuanId);

        if ($pengajuan->tahap !== 'VERIFIKASI_BERKAS') {
            return [
                'success' => false,
                'message' => 'Tahap pengajuan sudah berubah.'
            ];
        }

        $formDetails = $pengajuan->getFormPengajuan
            ->getFormPengajuanDetails;

        if ($formDetails->isEmpty()) {
            return [
                'success' => false,
                'message' => 'Dokumen persyaratan tidak ditemukan.'
            ];
        }

        // Ambil versi draft Kepegawaian yang belum disubmit.
        $draftVersion = ReviewPengajuan::where(
            'pengajuan_id',
            $pengajuanId
        )
            ->where('reviewer_type', 'kepegawaian')
            ->where('is_submitted', false)
            ->max('version');

        if ($draftVersion === null) {
            return [
                'success' => false,
                'message' => 'Belum ada action dokumen yang tersimpan.'
            ];
        }

        $draftReviews = ReviewPengajuan::where(
            'pengajuan_id',
            $pengajuanId
        )
            ->where('reviewer_type', 'kepegawaian')
            ->where('version', $draftVersion)
            ->where('is_submitted', false)
            ->get()
            ->keyBy('key');

        // Pastikan setiap dokumen sudah diperiksa.
        $missingDocuments = $formDetails->filter(
            function ($detail) use ($draftReviews) {
                return !$draftReviews->has($detail->key);
            }
        );

        if ($missingDocuments->isNotEmpty()) {
            return [
                'success' => false,
                'message' => 'Masih ada ' .
                    $missingDocuments->count() .
                    ' dokumen yang belum memiliki action tersimpan.'
            ];
        }

     
      

        // Sesuai aturan lama SI-LAKSAN:
        // semua approve = lanjut ke Komite.
        // Selain itu = dikembalikan untuk revisi.
        $allApproved = $formDetails->every(
            function ($detail) use ($draftReviews) {
                return $draftReviews->get($detail->key)->status
                    === 'approve';
            }
        );

        // Tandai draft sebagai sudah difinalisasi.
        $updatedRows = ReviewPengajuan::where(
            'pengajuan_id',
            $pengajuanId
        )
            ->where('reviewer_type', 'kepegawaian')
            ->where('version', $draftVersion)
            ->where('is_submitted', false)
            ->update([
                'is_submitted' => true
            ]);

        if ($updatedRows === 0) {
            return [
                'success' => false,
                'message' => 'Draft tidak dapat difinalisasi. Silakan muat ulang halaman.'
            ];
        }

        // Perbarui status dan tahap pengajuan.
        if ($allApproved) {
            $pengajuan->status = 'DALAM_PROSES';
            $pengajuan->tahap = 'SIDANG_KOMITE';

            $progressStatus = 'DISETUJUI';
            $keterangan =
                'Semua berkas diverifikasi, lanjut ke sidang komite.';
        } else {
            $pengajuan->status = 'REVISI';
            $pengajuan->tahap = 'PERLU_DILENGKAPI';

            $progressStatus = 'REVISI';
            $keterangan =
                'Ada berkas yang perlu diperbaiki oleh dosen.';
        }

        $pengajuan->save();

        // Catat progres final satu kali.
        ProgresPengajuan::create([
            'pengajuan_id' => $pengajuanId,
            'verified_by' => Auth::id(),
            'status' => $progressStatus,
            'tahap' => $pengajuan->tahap,
            'keterangan' => $keterangan,
        ]);

        return [
            'success' => true,
            'message' => $allApproved
                ? 'Review berhasil disubmit. Pengajuan diteruskan ke Komite Integritas.'
                : 'Review berhasil disubmit. Pengajuan dikembalikan kepada Dosen untuk revisi.'
        ];
    });

    if (!$result['success']) {
        return back()->with('error', $result['message']);
    }

    return redirect()
        ->route('kepegawaian.pengajuan.list')
        ->with('success', $result['message']);
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
