<?php

namespace App\Models;
use App\Models\User;
use App\Models\Periode;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pengajuan extends Model
{
    use HasUuids;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'form_pengajuan_id',
        'user_id',
        'status',
        'tahap',
        'ijazah_tertinggi',
        'disertasi',
        'sk_cpns',
        'sk_pns',
        'sk_pak_integrasi',
        'sk_pak_konversi',
        'dupak',
        'lbkd',
        'skp',
        'keabsahan_karil',
        'profile_penelitian',
        'peta_jabatan',
        'pengantar_dekan',
        'surat_hukuman_disiplin',
        'surat_status_pidana',
        'isian_pribadi',
        'sertifikat_pendidik',
        'sk_pengangkatan_fungsional_pertama',
        'sk_kenaikan_pangkat_terakhir',
        'sk_kenaikan_fungsional_terakhir',
        'pakta_integritas_validasi_karya',
        'sayrat_khusus',
    ];


    public function getUser(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getFormPengajuan(): BelongsTo {
        return $this->belongsTo(FormPengajuan::class, 'form_pengajuan_id', 'id');
    }

    public function getProgresPengajuans() : HasMany {
        return $this->hasMany(ProgresPengajuan::class, 'pengajuan_id', 'id');
    }

    public function getReviewPengajuans() : HasMany {
        return $this->hasMany(ReviewPengajuan::class, 'pengajuan_id', 'id');
    }

    public function getPeriode(): BelongsTo {
        return $this->belongsTo(Periode::class, 'periode_id', 'id');
    }

    public function getSidangPengajuan(): HasMany {
        return $this->hasMany(SidangPengajuan::class, 'pengajuan_id', 'id');
    }

    public function sidangKomiteTerakhir(): HasOne
    {
        return $this->hasOne(SidangPengajuan::class, 'pengajuan_id', 'id')
            ->where('sidang', 'KOMITE')
            ->latest('created_at');
    }

    public function sidangSenatTerakhir(): HasOne
    {
        return $this->hasOne(SidangPengajuan::class, 'pengajuan_id', 'id')
            ->where('sidang', 'SENAT')
            ->latest('created_at');
    }

    



    // RELASI KE DOSEN
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // RELASI KE PERIODE
    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }



    public function getSk(): HasOne {
        return $this->hasOne(SkJabatan::class, 'pengajuan_id', 'id');
    }

}