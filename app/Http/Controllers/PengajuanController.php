<?php

namespace App\Http\Controllers;

use App\Models\FormPengajuan;
use App\Models\Pengajuan;
use App\Models\ProgresPengajuan;
use App\Models\SkJabatan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use ZipArchive;

class PengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function submit(Request $request, $id)
    {
        $user = Auth::user();

        $form = FormPengajuan::with('getFormPengajuanDetails')->findOrFail($id);
        $formDetails = $form->getFormPengajuanDetails;

$rules = [
    'periode_id' => ['required', 'exists:periodes,id'],
];

foreach ($formDetails as $detail) {
    $rules[$detail->key] = [
        'nullable',
        'file',
        'mimes:pdf',
        'max:3072',
    ];
}

$request->validate($rules, [
    '*.mimes' => 'Berkas wajib menggunakan format PDF.',
    '*.max' => 'Ukuran setiap berkas maksimal 3MB.',
]);


        $uploadedFiles = [];

        $pengajuan = new Pengajuan();
        $pengajuan->user_id = $user->id;
        $pengajuan->form_pengajuan_id = $id;
        $pengajuan->periode_id = $request->input('periode_id');

        $requestCount = 0;

        foreach ($formDetails as $detail) {
            if ($request->hasFile($detail->key)) {
                
                $key = $detail->key;

                $file = $request->file($key);
                $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $destinationPath = "pengajuan/{$user->email}/{$key}";
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                Storage::putFileAs($destinationPath, $file, $fileName);
                $uploadedFiles[$key] = $destinationPath . '/' . $fileName;

                $pengajuan->$key = $uploadedFiles[$key];
                $requestCount++;
            }
        }

        if ($request->pengajuan && $requestCount == $formDetails->count()) {
            $message = 'Pengajuan anda berhasil dan sedang dalam proses review oleh staff kepegawaian';
            $pengajuan->status = 'BARU';
            $pengajuan->tahap = 'VERIFIKASI_BERKAS';
            $keterangan = 'Form Pengajuan telah diselesaikan, dikirim ke staff kepegawaian untuk di verifikasi';
        } else {
            $message = 'Pengajuan anda berhasil disimpan, namun belum di ajukan dan belum dapat diverifikasi staff kepegawaian.';
            $pengajuan->status = 'DRAFT';
            $pengajuan->tahap = 'PERLU_DILENGKAPI';
            $keterangan = 'Form Pengajuan disimpan, Perlu dilengkapi agar dapat diverifikasi oleh staff kepegawaian';
        }

        $pengajuan->save();

        
        ProgresPengajuan::create([
            'pengajuan_id' => $pengajuan->id,
            'verified_by' => $user->id,
            'status' => $pengajuan->status,
            'tahap' => $pengajuan->tahap,
            'keterangan' => $keterangan,
        ]);

        return redirect()->route('dosen_dashboard')->with('success', $message);
    }

    public function pengajuan_view($id)
    {
        $pengajuan = Pengajuan::find($id);
        return view('Dosen.ViewPengajuan', compact('pengajuan'));
    }

    public function pengajuan_edit($id)
    {
        $pengajuan = Pengajuan::find($id);
        return view('Dosen.EditPengajuan', compact('pengajuan'));
    }
    public function pengajuan_edit_submit(Request $request, $id)
    {
        $uploadedFiles = [];

        $pengajuan = Pengajuan::find($id);
        $formDetails = $pengajuan->getFormPengajuan->getFormPengajuanDetails;
$rules = [];

foreach ($formDetails as $detail) {
    $rules[$detail->key] = [
        'nullable',
        'file',
        'mimes:pdf',
        'max:3072',
    ];
}

$request->validate($rules, [
    '*.mimes' => 'Berkas wajib menggunakan format PDF.',
    '*.max' => 'Ukuran setiap berkas maksimal 3 MB.',
]);

        $user = $pengajuan->getUser;

        $requestCount = 0;

        foreach ($formDetails as $detail) {

            $key = $detail->key;

            if ($request->hasFile($detail->key)) {    

                if ($pengajuan->$key !== null && Storage::exists($pengajuan->$key)) {
                    Storage::delete($pengajuan->$key);
                }

                $file = $request->file($key);
                $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $destinationPath = "pengajuan/{$user->email}/{$key}";
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                Storage::putFileAs($destinationPath, $file, $fileName);
                $uploadedFiles[$key] = $destinationPath . '/' . $fileName;

                $pengajuan->$key = $uploadedFiles[$key];
            }
            
            if ($pengajuan->$key !== null) {
                $requestCount++;
            }
        }

        if ($request->pengajuan && $requestCount == $formDetails->count()) {
            $message = 'Pengajuan anda berhasil dan sedang dalam proses review oleh staff kepegawaian';
            $pengajuan->status = 'BARU';
            $pengajuan->tahap = 'VERIFIKASI_BERKAS';
            $keterangan = 'Form Pengajuan telah diselesaikan, dikirim ke staff kepegawaian untuk di verifikasi';
        } else {
            $message = 'Pengajuan anda berhasil disimpan, namun belum di ajukan dan belum dapat diverifikasi staff kepegawaian.';
            $pengajuan->status = 'DRAFT';
            $pengajuan->tahap = 'PERLU_DILENGKAPI';
            $keterangan = 'Form Pengajuan disimpan, Namun belum lengkap Perlu dilengkapi agar dapat diverifikasi oleh staff kepegawaian';
        }

        $pengajuan->save();


        ProgresPengajuan::create([
            'pengajuan_id' => $pengajuan->id,
            'verified_by' => $user->id,
            'status' => $pengajuan->status,
            'tahap' => $pengajuan->tahap,
            'keterangan' => $keterangan,
        ]);

        return redirect()->route('dosen_dashboard')->with('success', $message);
    }

    public function kepegawaian_pengajuan_list()
    {
        $pengajuans = Pengajuan::all();
        return view('Kepegawaian.ListPengajuan', compact('pengajuans'));
    }

   
public function pengajuan_review($id)
{
    $pengajuan = Pengajuan::findOrFail($id);

    // Cari versi draft review Kepegawaian yang belum disubmit.
    $draftVersion = $pengajuan->getReviewPengajuans()
        ->where('reviewer_type', 'kepegawaian')
        ->where('is_submitted', false)
        ->max('version');

    // Siapkan koleksi draft, meskipun belum ada review tersimpan.
    $draftReviews = collect();

    // Jika ada draft, ambil action dokumen dari versi tersebut.
    if ($draftVersion !== null) {
        $draftReviews = $pengajuan->getReviewPengajuans()
            ->where('reviewer_type', 'kepegawaian')
            ->where('is_submitted', false)
            ->where('version', $draftVersion)
            ->get()
            ->keyBy('key');
    }

    return view(
        'Kepegawaian.ReviewPengajuan',
        compact('pengajuan', 'draftReviews', 'draftVersion')
    );
}


    public function pengajuan_sister($id)
    {
        $pengajuan = Pengajuan::find($id);
        return view('Kepegawaian.SisterPengajuan', compact('pengajuan'));
    }

    public function pengajuan_progress($id){
        $pengajuan = Pengajuan::find($id);
        return view('Dosen.ProgressPengajuan', compact('pengajuan'));
    }


    public function comite_pengajuan_list()
    {
        $pengajuans = Pengajuan::all();
        return view('Comite.ListPengajuan', compact('pengajuans'));
    }

    public function sidang_komite_view($id){
        $pengajuan = Pengajuan::find($id);
        return view('Comite.SidangKomite', compact('pengajuan'));
    }

    public function getFile($email, $key, $file){
        $path = "private/pengajuan/$email/$key/$file";
        $fullPath = storage_path('app/' . $path);
        if (!file_exists($fullPath)) {
            abort(404);
        }
        return response()->file($fullPath);
    }

    public function getFileSidang($email, $file){
        $path = "private/sidang/$email/$file";
        $fullPath = storage_path('app/' . $path);
        if (!file_exists($fullPath)) {
            abort(404);
        }
        return response()->file($fullPath);
    }

    public function sidang_senat_view($id){
        $pengajuan = Pengajuan::find($id);
        return view('Senat.SidangSenat', compact('pengajuan'));
    }

    public function senat_pengajuan_list()
    {
        $pengajuans = Pengajuan::all();
        return view('Senat.ListPengajuan', compact('pengajuans'));
    }

    
public function download_pengajuan($id_pengajuan)
{
    $pengajuan = Pengajuan::with([
        'getUser',
        'getFormPengajuan.getFormPengajuanDetails',
        'sidangKomiteTerakhir',
        'sidangSenatTerakhir',
    ])->findOrFail($id_pengajuan);

    // 1. Ambil versi review final Kepegawaian.
    // Draft autosave tidak boleh masuk ZIP.
    $lastVersion = $pengajuan->getReviewPengajuans()
        ->where('reviewer_type', 'kepegawaian')
        ->where('is_submitted', true)
        ->max('version');

    if ($lastVersion === null) {
        return back()->with(
            'error',
            'Belum ada verifikasi final dari Kepegawaian.'
        );
    }

    // 2. Ambil dokumen yang disetujui
    // pada versi final terbaru.
    $approvedKeys = $pengajuan->getReviewPengajuans()
        ->where('reviewer_type', 'kepegawaian')
        ->where('is_submitted', true)
        ->where('version', $lastVersion)
        ->where('status', 'approve')
        ->pluck('key')
        ->all();

    if (empty($approvedKeys)) {
        return back()->with(
            'error',
            'Tidak ada dokumen yang disetujui Kepegawaian.'
        );
    }

    $formPengajuan = $pengajuan->getFormPengajuan;

    if (!$formPengajuan) {
        return back()->with(
            'error',
            'Form pengajuan tidak ditemukan.'
        );
    }

    $formDetails = $formPengajuan
        ->getFormPengajuanDetails()
        ->orderBy('order', 'ASC')
        ->get();

    $filesToZip = [];

    // 3. Masukkan berkas yang sudah APPROVE.
    foreach ($formDetails as $detail) {
        $column = $detail->key;

        if (!in_array($column, $approvedKeys, true)) {
            continue;
        }

        $path = $pengajuan->{$column};

        if (empty($path)) {
            return back()->with(
                'error',
                'Berkas ' . $detail->title . ' belum tersedia.'
            );
        }

        $fullPath = storage_path(
            'app/private/' . ltrim($path, '/\\')
        );

        if (!is_file($fullPath)) {
            return back()->with(
                'error',
                'File ' . $detail->title . ' tidak ditemukan.'
            );
        }

        $filesToZip[] = [
            'source' => $fullPath,
            'destination' =>
                '01_Approve_Kepegawaian/' .
                $column . '-' . basename($path),
        ];
    }

    if (empty($filesToZip)) {
        return back()->with(
            'error',
            'Tidak ada file yang bisa diunduh.'
        );
    }

    // 4. Ambil Berita Acara Komite,
    // hanya jika hasil sidang terakhir LULUS.
    $sidangKomite = $pengajuan->sidangKomiteTerakhir;

    if (
        $sidangKomite &&
        $sidangKomite->status === 'LULUS'
    ) {
        $pathKomite = $sidangKomite->berita_acara;

        $fullPathKomite = $pathKomite
            ? storage_path(
                'app/private/' . ltrim($pathKomite, '/\\')
            )
            : null;

        if (!$fullPathKomite || !is_file($fullPathKomite)) {
            return back()->with(
                'error',
                'Berita Acara Komite tidak ditemukan.'
            );
        }

        $filesToZip[] = [
            'source' => $fullPathKomite,
            'destination' =>
                '02_Approve_Komite/Berita_Acara_Komite-' .
                basename($pathKomite),
        ];
    }

    // 5. Ambil Berita Acara Senat,
    // jika hasil sidang terakhir LULUS.
    $sidangSenat = $pengajuan->sidangSenatTerakhir;

    if (
        $sidangSenat &&
        $sidangSenat->status === 'LULUS'
    ) {
        $pathSenat = $sidangSenat->berita_acara;

        $fullPathSenat = $pathSenat
            ? storage_path(
                'app/private/' . ltrim($pathSenat, '/\\')
            )
            : null;

        if (!$fullPathSenat || !is_file($fullPathSenat)) {
            return back()->with(
                'error',
                'Berita Acara Senat tidak ditemukan.'
            );
        }

        $filesToZip[] = [
            'source' => $fullPathSenat,
            'destination' =>
                '03_Hasil_Senat/Berita_Acara_Senat-' .
                basename($pathSenat),
        ];
    }

    // 6. Siapkan folder ZIP sementara.
    // Gunakan private storage agar tidak publik.
    $tempDirectory = storage_path('app/private/tmp');

    File::ensureDirectoryExists($tempDirectory);

    // Nama unik agar download bersamaan tidak bentrok.
    $zipFilePath = $tempDirectory .
        '/Pengajuan-' . Str::uuid() . '.zip';

    $zip = new ZipArchive();

    $opened = $zip->open(
        $zipFilePath,
        ZipArchive::CREATE | ZipArchive::OVERWRITE
    );

    if ($opened !== true) {
        return back()->with(
            'error',
            'Gagal membuat file ZIP.'
        );
    }

    // 7. Masukkan file ke ZIP.
    foreach ($filesToZip as $file) {
        $success = $zip->addFile(
            $file['source'],
            $file['destination']
        );

        if (!$success) {
            $zip->close();
            File::delete($zipFilePath);

            return back()->with(
                'error',
                'Gagal menambahkan berkas ke ZIP.'
            );
        }
    }

    // 8. Selesaikan pembuatan ZIP.
    if (!$zip->close()) {
        File::delete($zipFilePath);

        return back()->with(
            'error',
            'Gagal menyelesaikan file ZIP.'
        );
    }

    // 9. Download lalu hapus ZIP sementara.
    $downloadName = 'Pengajuan-' .
        $pengajuan->id . '.zip';

    return response()
        ->download($zipFilePath, $downloadName)
        ->deleteFileAfterSend(true);
}

    public function kepegawaian_pengajuan_approved(Request $request, $id_pengajuan){
        $pengajuan = Pengajuan::find($id_pengajuan);

    $request->validate([
    'sk' => [
        'required',
        'file',
        'mimes:pdf',
        'max:3072',
    ],
], [
    'sk.required' => 'File SK wajib diunggah.',
    'sk.mimes' => 'File SK wajib menggunakan format PDF.',
    'sk.max' => 'Ukuran file SK maksimal 3 MB.',
]);

        ProgresPengajuan::create([
            'pengajuan_id' => $pengajuan->id,
            'verified_by' => Auth::user()->id,
            'status' => 'DISETUJUI',
            'tahap' => 'PENGAJUAN_SISTER',
            'keterangan' => 'Pengajuan ke Sister oleh Kepegawaian telah berhasil dan disetujui',
        ]);
        $pengajuan->status = 'DISETUJUI';
        $pengajuan->tahap = 'SK_TERBIT';
        $pengajuan->save();

        $file = $request->file('sk');
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $destinationPath = "sk/{$pengajuan->getUser->email}";
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }
        Storage::putFileAs($destinationPath, $file, $fileName);
        $fullPath = $destinationPath . '/' . $fileName;

        SkJabatan::create([
            'pengajuan_id' => $pengajuan->id,
            'uploaded_by' => Auth::user()->id,
            'sk' => $fullPath,
        ]);

        return redirect()->route('kepegawaian.pengajuan.list')->with('success', 'Berhasil Upload SK');
    }

    public function kepegawaian_pengajuan_rejected(Request $request, $id_pengajuan){
        $pengajuan = Pengajuan::find($id_pengajuan);
        ProgresPengajuan::create([
            'pengajuan_id' => $pengajuan->id,
            'verified_by' => Auth::user()->id,
            'status' => 'DITOLAK',
            'tahap' => 'PENGAJUAN_SISTER',
            'keterangan' => 'Pengajuan anda ditolak oleh Sister / tidak valid oleh kepegawaian',
        ]);
        $pengajuan->status = 'DITOLAK';
        $pengajuan->tahap = 'SK_KENAIKAN';
        $pengajuan->save();
        return redirect()->route('kepegawaian.pengajuan.list')->with('success', 'Berhasil menolak Pengajuan');
    }

    public function download_sk($id_pengajuan) {
        $pengajuan = Pengajuan::find($id_pengajuan);
        $skPath = $pengajuan->getSk->sk;
        $sk = storage_path('app/private/' . $skPath);
        return response()->download($sk);
    }
 

public function pengajuan_view_comite($id)
{
    $pengajuan = Pengajuan::with([
        'getFormPengajuan.getFormPengajuanDetails',
        'getReviewPengajuans',
        'getProgresPengajuans.getUser',
    ])->findOrFail($id);

    
$lastVersion = $pengajuan->getReviewPengajuans
    ->where('reviewer_type', 'kepegawaian')
    ->where('is_submitted', true)
    ->max('version');


    $verifiedFiles = $pengajuan->getReviewPengajuans
                        ->where('version', $lastVersion)
                        ->where('status', 'approve');

    return view('Comite.ViewPengajuan', compact('pengajuan', 'verifiedFiles'));
}

public function comite_get_file($id, $key)
{
    $pengajuan = Pengajuan::findOrFail($id);
    $filePath  = $pengajuan->$key;

    if (!$filePath) abort(404);

    $fullPath = storage_path('app/private/' . $filePath);
    if (!file_exists($fullPath)) abort(404);

    return response()->file($fullPath);
}

public function comite_download_pengajuan($id)
{
    $pengajuan = Pengajuan::with([
        'getFormPengajuan.getFormPengajuanDetails',
        'getReviewPengajuans',
    ])->findOrFail($id);

    if (!File::exists(storage_path('app/public/tmp'))) {
        File::makeDirectory(storage_path('app/public/tmp'), 0755, true);
    }

    $zipFileName = 'Comite-' . $pengajuan->getUser->email . '.zip';
    $zipFilePath = storage_path('app/public/tmp/' . $zipFileName);
    $zip = new ZipArchive;

    if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        
$lastVersion = $pengajuan->getReviewPengajuans
    ->where('reviewer_type', 'kepegawaian')
    ->where('is_submitted', true)
    ->max('version');


        foreach ($pengajuan->getFormPengajuan->getFormPengajuanDetails()->orderBy('order', 'ASC')->get() as $detail) {
            $column = $detail->key;

            $review = $pengajuan->getReviewPengajuans
                        ->where('key', $column)
                        ->where('version', $lastVersion)
                        ->where('status', 'approve')
                        ->first();

            if (!$review) continue;
            if ($pengajuan->$column === null) continue;

            $fullPath = storage_path('app/private/' . $pengajuan->$column);
            if (file_exists($fullPath)) {
                $zip->addFile($fullPath, 'berkas/' . $column . '-' . basename($pengajuan->$column));
            }
        }

        $zip->close();
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }

    return back()->with('error', 'Gagal membuat file ZIP.');
}

public function pengajuan_view_senat($id)
{
    $pengajuan = Pengajuan::with([
        'getFormPengajuan.getFormPengajuanDetails',
        'getReviewPengajuans',
        'getProgresPengajuans.getUser',
    ])->findOrFail($id);

    
$lastVersion = $pengajuan->getReviewPengajuans
    ->where('reviewer_type', 'kepegawaian')
    ->where('is_submitted', true)
    ->max('version');

    $verifiedFiles = $pengajuan->getReviewPengajuans
                        ->where('version', $lastVersion)
                        ->where('status', 'approve');

    return view('Senat.ViewPengajuan', compact('pengajuan', 'verifiedFiles')); // fix ini
}

public function senat_get_file($id, $key)
{
    $pengajuan = Pengajuan::findOrFail($id);
    $filePath  = $pengajuan->$key;

    if (!$filePath) abort(404);

    $fullPath = storage_path('app/private/' . $filePath);
    if (!file_exists($fullPath)) abort(404);

    return response()->file($fullPath);
}

public function senat_download_pengajuan($id)
{
    $pengajuan = Pengajuan::with([
        'getFormPengajuan.getFormPengajuanDetails',
        'getReviewPengajuans',
    ])->findOrFail($id);

    if (!File::exists(storage_path('app/public/tmp'))) {
        File::makeDirectory(storage_path('app/public/tmp'), 0755, true);
    }

    $zipFileName = 'Senat-' . $pengajuan->getUser->email . '.zip';
    $zipFilePath = storage_path('app/public/tmp/' . $zipFileName);
    $zip = new ZipArchive;

    if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        
$lastVersion = $pengajuan->getReviewPengajuans
    ->where('reviewer_type', 'kepegawaian')
    ->where('is_submitted', true)
    ->max('version');


        foreach ($pengajuan->getFormPengajuan->getFormPengajuanDetails()->orderBy('order', 'ASC')->get() as $detail) {
            $column = $detail->key;

            $review = $pengajuan->getReviewPengajuans
                        ->where('key', $column)
                        ->where('version', $lastVersion)
                        ->where('status', 'approve')
                        ->first();

            if (!$review) continue;
            if ($pengajuan->$column === null) continue;

            $fullPath = storage_path('app/private/' . $pengajuan->$column);
            if (file_exists($fullPath)) {
                $zip->addFile($fullPath, 'berkas/' . $column . '-' . basename($pengajuan->$column));
            }
        }

        $zip->close();
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }

    return back()->with('error', 'Gagal membuat file ZIP.');
}

public function pengajuan_view_kepegawaian($id)
{
    $pengajuan = Pengajuan::with([
        'getFormPengajuan.getFormPengajuanDetails',
        'getReviewPengajuans',
        'getProgresPengajuans.getUser',
    ])->findOrFail($id);

    return view('Kepegawaian.ViewPengajuan', compact('pengajuan'));
}

public function pengajuan_view_komite($id)
{
    $pengajuan = Pengajuan::with([
        'getFormPengajuan.getFormPengajuanDetails',
        'getReviewPengajuans',
        'getProgresPengajuans.getUser',
    ])->findOrFail($id);

    return view('Comite.ViewPengajuanDetail', compact('pengajuan'));
}
public function pengajuan_view_senat_detail($id)
{
    $pengajuan = Pengajuan::with([
        'getFormPengajuan.getFormPengajuanDetails',
        'getReviewPengajuans',
        'getProgresPengajuans.getUser',
    ])->findOrFail($id);

    return view('Senat.ViewPengajuanDetail', compact('pengajuan'));
}

}