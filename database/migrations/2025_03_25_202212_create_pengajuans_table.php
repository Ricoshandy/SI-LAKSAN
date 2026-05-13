<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('form_pengajuan_id')->references('id')->on('form_pengajuans');
            $table->foreignUuid('periode_id')->references('id')->on('periodes');
            $table->foreignUuid('user_id')->references('id')->on('users');
            $table->string('ijazah_tertinggi')->nullable();
            $table->string('disertasi')->nullable();
            $table->string('sk_cpns')->nullable();
            $table->string('sk_pns')->nullable();
            $table->string('sk_pak_integrasi')->nullable();
            $table->string('sk_pak_konversi')->nullable();
            $table->string('dupak')->nullable();
            $table->string('lbkd')->nullable();
            $table->string('skp')->nullable();
            $table->string('keabsahan_karil')->nullable();
            $table->string('profile_penelitian')->nullable();
            $table->string('peta_jabatan')->nullable();
            $table->string('pengantar_dekan')->nullable();
            $table->string('surat_hukuman_disiplin')->nullable();
            $table->string('surat_status_pidana')->nullable();
            $table->string('isian_pribadi')->nullable();
            $table->string('sertifikat_pendidik')->nullable();
            $table->string('sk_pengangkatan_fungsional_pertama')->nullable();
            $table->string('sk_kenaikan_pangkat_terakhir')->nullable();
            $table->string('sk_kenaikan_fungsional_terakhir')->nullable();
            $table->string('pakta_integritas_validasi_karya')->nullable();
            $table->string('sayrat_khusus')->nullable();
            $table->enum('status', ['DRAFT', 'BARU', 'DALAM_PROSES', 'DISETUJUI', 'DITOLAK', 'REVISI'])->default('DRAFT');
            $table->enum('tahap', ['PERLU_DILENGKAPI','VERIFIKASI_BERKAS', 'SIDANG_KOMITE', 'SIDANG_SENAT', 'PENGAJUAN_SISTER', 'SK_KENAIKAN', 'PENERBITAN_SK', 'SK_TERBIT'])->default('PERLU_DILENGKAPI');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};