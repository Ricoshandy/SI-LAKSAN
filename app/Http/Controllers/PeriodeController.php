<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;
use App\Models\Pengajuan;

class PeriodeController extends Controller
{
    public function kepegawaian_periode_list()
    {
        $periodes = Periode::all();
        return view('Kepegawaian.ListPeriode', compact('periodes'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after:date_start',
        ]);

        $newStart = $request->input('date_start');
        $newEnd = $request->input('date_end');

        $overlap = Periode::where(function ($query) use ($newStart, $newEnd) {
            $query->where('date_start', '<=', $newEnd)
                ->where('date_end', '>=', $newStart);
        })->exists();

        if ($overlap) {
            return redirect()->back()
                ->withErrors(['overlap' => 'Tanggal periode yang dimasukkan tumpang tindih dengan periode yang sudah ada.'])
                ->withInput();
        }

        Periode::create([
            'name' => $request->input('name'),
            'date_start' => $newStart,
            'date_end' => $newEnd,
        ]);

        return redirect()->route('kepegawaian.periode.list')
            ->with('success', 'Berhasil Menambahkan Periode');
    }


public function viewPengajuan($id)
{
    $pengajuans = Pengajuan::with('user')
        ->where('periode_id', $id)
        ->get();

    return view('Kepegawaian.ViewPeriodePengajuan', compact('pengajuans'));
}

    public function edit(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date_start' => 'required|date',
            'date_end' => 'required|date|after:date_start',
        ]);

        $periode = Periode::findOrFail($id);

        $newStart = $request->input('date_start');
        $newEnd = $request->input('date_end');

        $overlap = Periode::where('id', '!=', $periode->id)
            ->where(function ($query) use ($newStart, $newEnd) {
                $query->where('date_start', '<=', $newEnd)
                    ->where('date_end', '>=', $newStart);
            })->exists();

        if ($overlap) {
            return redirect()->back()
                ->withErrors(['overlap' => 'Tanggal periode yang dimasukkan tumpang tindih dengan periode lain.'])
                ->withInput();
        }

        $periode->update([
            'name' => $request->input('name'),
            'date_start' => $newStart,
            'date_end' => $newEnd,
        ]);

        return redirect()->route('kepegawaian.periode.list')
            ->with('success', 'Berhasil Mengupdate Periode');
    }

  public function delete($id)
{
    $periode = Periode::findOrFail($id);

    // Cek apakah periode masih digunakan oleh pengajuan
    if ($periode->pengajuans()->count() > 0) {
        return redirect()->route('kepegawaian.periode.list')
            ->withErrors([
                'delete_error' => 
                'Periode tidak dapat dihapus karena masih digunakan dalam pengajuan kenaikan jabatan.'
            ]);
    }

    $periode->delete();

    return redirect()->route('kepegawaian.periode.list')
        ->with('success', 'Periode berhasil dihapus.');
}

    }

