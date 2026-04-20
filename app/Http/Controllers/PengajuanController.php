<?php

namespace App\Http\Controllers;

use App\Models\FormPengajuan;
use App\Models\Pengajuan;
use App\Models\ProgresPengajuan;
use App\Models\SkJabatan;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Str;
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
        $pengajuan = Pengajuan::find($id);
        return view('Kepegawaian.ReviewPengajuan', compact('pengajuan'));
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

    public function download_pengajuan($id_pengajuan) {
        $pengajuan = Pengajuan::find($id_pengajuan);
        
        if (!File::exists(storage_path('app/public/tmp'))) {
            File::makeDirectory(storage_path('app/public/tmp'), 0755, true);
        }

        $zipFileName = 'Pengajuans-' . $pengajuan->getUser->email . '.zip';
        $zipFilePath = storage_path('app/public/tmp/' . $zipFileName);
        $zip = new ZipArchive;

        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            foreach ( $pengajuan->getFormPengajuan->getFormPengajuanDetails()->orderBy('order', 'ASC')->get() as $key ) {
                $column = $key->key;
                $path = '/'. $pengajuan->$column;
                $fullPath = storage_path('app/private/' . $path);
                
                if (file_exists($fullPath)) {
                    $filename = 'pengajuan/' . $column . '-' . basename($path);
                    $zip->addFile($fullPath, $filename);
                }
            }

            $sidangKomite = $pengajuan->sidangKomiteTerakhir;
            $sidangSenat = $pengajuan->sidangSenatTerakhir;
            $sidangKomitePath = storage_path('app/private/' . $sidangKomite->berita_acara);
            $sidangSenatePath = storage_path('app/private/' . $sidangSenat->berita_acara);

            if ($sidangKomite && file_exists($sidangKomitePath)) {
                $zip->addFile($sidangKomitePath, 'sidang/komite-' . basename($sidangKomite->berita_acara));
            }
            if ($sidangSenat && file_exists($sidangSenatePath)) {
                $zip->addFile($sidangSenatePath, 'sidang/senat-' . basename($sidangSenat->berita_acara));
            }
            
            $zip->close();
    
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } else {
            return back()->with('error', 'Gagal membuat file ZIP.');
        }
    }


// Method untuk Senat - View Berkas Terverifikasi
public function senat_pengajuan_view($id)
{
    $pengajuan = Pengajuan::findOrFail($id);

    // Cek tahap harus SIDANG_SENAT
    if ($pengajuan->tahap !== 'SIDANG_SENAT') {
        abort(403, 'Unauthorized');
    }

    // Ambil berkas yang terverifikasi
    $verifiedFiles = $pengajuan->getReviewPengajuans()
        ->where('is_verified', true)
        ->orderByDesc('version')
        ->get()
        ->unique('key');

    return view('Senat.ViewPengajuan', compact('pengajuan', 'verifiedFiles'));
}

// Method untuk Senat - Serve File
public function senat_serve_file($id, $key)
{
    try {
        $pengajuan = Pengajuan::findOrFail($id);

        // Cek tahap harus SIDANG_SENAT
        if ($pengajuan->tahap !== 'SIDANG_SENAT') {
            abort(403, 'Unauthorized');
        }

        $filePath = $pengajuan->$key;

        if (!$filePath) {
            abort(404, 'File path not found');
        }

        $fullPath = storage_path('app/private/' . $filePath);

        if (!file_exists($fullPath)) {
            abort(404, 'File not found');
        }

        $mimeType = mime_content_type($fullPath);

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline',
            'Access-Control-Allow-Origin' => '*',
        ]);

    } catch (\Exception $e) {
        \Log::error('Error in senat_serve_file: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

// Method untuk Senat - Download Berkas ZIP
public function senat_download_berkas($id)
{
    $pengajuan = Pengajuan::findOrFail($id);

    if ($pengajuan->tahap !== 'SIDANG_SENAT') {
        abort(403, 'Unauthorized');
    }

    // Ambil berkas yang terverifikasi
    $verifiedFiles = $pengajuan->getReviewPengajuans()
        ->where('is_verified', true)
        ->orderByDesc('version')
        ->get()
        ->unique('key');

    $zipFileName = 'Berkas-Terverifikasi-' . $pengajuan->getUser->name . '.zip';
    $zipFilePath = storage_path('app/public/tmp/' . $zipFileName);

    if (!File::exists(storage_path('app/public/tmp'))) {
        File::makeDirectory(storage_path('app/public/tmp'), 0755, true);
    }

    $zip = new ZipArchive;

    if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        foreach ($verifiedFiles as $review) {
            $key = $review->key;
            $filePath = $pengajuan->$key;

            if ($filePath) {
                $fullPath = storage_path('app/private/' . $filePath);
                if (file_exists($fullPath)) {
                    $zip->addFile($fullPath, $key . '-' . basename($filePath));
                }
            }
        }

        $zip->close();

        return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
    } else {
        return back()->with('error', 'Gagal membuat file ZIP.');
    }
}


    
    public function kepegawaian_pengajuan_approved(Request $request, $id_pengajuan){
        $pengajuan = Pengajuan::find($id_pengajuan);
        ProgresPengajuan::create([
            'pengajuan_id' => $pengajuan->id,
            'verified_by' => Auth::user()->id,
            'status' => 'DISETUJUI',
            'tahap' => 'PENGAJUAN_SISTER',
            'keterangan' => 'Pengajuan ke Sister oleh Kepegawaian telah berhasil dan disetujui',
        ]);
        $pengajuan->status = 'DISETUJUI';
        $pengajuan->tahap = 'SK_KENAIKAN';
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

    public function comite_pengajuan_view($id){
        $pengajuan = Pengajuan::find($id);

    if ($pengajuan->tahap !== 'SIDANG_KOMITE') {
        abort(403, 'Unauthorized');
    }

    // Ambil berkas yang udah diverifikasi, group by key, ambil version terbaru
    $verifiedFiles = $pengajuan->getReviewPengajuans()
        ->where('is_verified', true)
        ->orderByDesc('version')
        ->get()
        ->unique('key');

    return view('Comite.ViewPengajuan', compact('pengajuan', 'verifiedFiles'));
    }

    public function comite_download_berkas($id){
{
    $pengajuan = Pengajuan::findOrFail($id);

    if ($pengajuan->tahap !== 'SIDANG_KOMITE') {
        abort(403, 'Unauthorized');
    }

    // Ambil berkas yang terverifikasi
    $verifiedFiles = $pengajuan->getReviewPengajuans()
        ->where('is_verified', true)
        ->orderByDesc('version')
        ->get()
        ->unique('key');

    $zipFileName = 'Berkas-Terverifikasi-' . $pengajuan->getUser->name . '.zip';
    $zipFilePath = storage_path('app/public/tmp/' . $zipFileName);

    if (!File::exists(storage_path('app/public/tmp'))) {
        File::makeDirectory(storage_path('app/public/tmp'), 0755, true);
    }

    $zip = new ZipArchive;

    if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        foreach ($verifiedFiles as $review) {
            $key = $review->key;
            $filePath = $pengajuan->$key;

            if ($filePath) {
                $fullPath = storage_path('app/private/' . $filePath);
                if (file_exists($fullPath)) {
                    $zip->addFile($fullPath, $key . '-' . basename($filePath));
                }
            }
        }

        $zip->close();

        return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
    } else {
        return back()->with('error', 'Gagal membuat file ZIP.');
    }
}
    }

public function comite_serve_file($id, $key)
{
     try {
        $pengajuan = Pengajuan::findOrFail($id);

        // Cek tahap harus SIDANG_KOMITE
        if ($pengajuan->tahap !== 'SIDANG_KOMITE') {
            abort(403, 'Unauthorized');
        }

        $filePath = $pengajuan->$key;

        if (!$filePath) {
            abort(404, 'File path not found');
        }

        $fullPath = storage_path('app/private/' . $filePath);

        if (!file_exists($fullPath)) {
            abort(404, 'File not found');
        }

        $mimeType = mime_content_type($fullPath);

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline',
            'Access-Control-Allow-Origin' => '*',
        ]);

    } catch (\Exception $e) {
        \Log::error('Error in comite_serve_file: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
    
public function serveFile($id, $key)
{
    try {
        $pengajuan = Pengajuan::findOrFail($id);
        
        // KEPEGAWAIAN BOLEH AKSES SEMUA FILE (ga perlu cek user_id)
        // Kalo mau lebih strict, cek role user:
        // if (auth()->user()->role !== 'kepegawaian' && $pengajuan->user_id !== auth()->id()) {
        //     abort(403, 'Unauthorized');
        // }
        
        $filePath = $pengajuan->$key;
        
        if (!$filePath) {
            abort(404, 'File path not found');
        }
        
        $fullPath = storage_path('app/private/' . $filePath);
        
        if (!file_exists($fullPath)) {
            abort(404, 'File not found');
        }
        
        // Cek MIME type
        $mimeType = mime_content_type($fullPath);
        
        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline',
            'Access-Control-Allow-Origin' => '*',
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Error in serveFile: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

}
