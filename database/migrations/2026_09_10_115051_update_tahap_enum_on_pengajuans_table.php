<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE pengajuans
            MODIFY COLUMN tahap ENUM(
                'PERLU_DILENGKAPI',
                'VERIFIKASI_BERKAS',
                'SIDANG_KOMITE',
                'SIDANG_SENAT',
                'PENGAJUAN_SISTER',
                'SK_KENAIKAN',
                'PENERBITAN_SK',
                'SK_TERBIT'
            ) NOT NULL DEFAULT 'PERLU_DILENGKAPI'
        ");

        DB::statement("
            ALTER TABLE progres_pengajuans
            MODIFY COLUMN tahap ENUM(
                'PERLU_DILENGKAPI',
                'VERIFIKASI_BERKAS',
                'SIDANG_KOMITE',
                'SIDANG_SENAT',
                'PENGAJUAN_SISTER',
                'SK_KENAIKAN',
                'PENERBITAN_SK',
                'SK_TERBIT'
            ) NOT NULL DEFAULT 'PERLU_DILENGKAPI'
        ");
    }

    public function down(): void
    {
        DB::table('pengajuans')
            ->whereIn('tahap', ['PENERBITAN_SK', 'SK_TERBIT'])
            ->update(['tahap' => 'SK_KENAIKAN']);

        DB::table('progres_pengajuans')
            ->whereIn('tahap', ['PENERBITAN_SK', 'SK_TERBIT'])
            ->update(['tahap' => 'SK_KENAIKAN']);

        DB::statement("
            ALTER TABLE pengajuans
            MODIFY COLUMN tahap ENUM(
                'PERLU_DILENGKAPI',
                'VERIFIKASI_BERKAS',
                'SIDANG_KOMITE',
                'SIDANG_SENAT',
                'PENGAJUAN_SISTER',
                'SK_KENAIKAN'
            ) NOT NULL DEFAULT 'PERLU_DILENGKAPI'
        ");

        DB::statement("
            ALTER TABLE progres_pengajuans
            MODIFY COLUMN tahap ENUM(
                'PERLU_DILENGKAPI',
                'VERIFIKASI_BERKAS',
                'SIDANG_KOMITE',
                'SIDANG_SENAT',
                'PENGAJUAN_SISTER',
                'SK_KENAIKAN'
            ) NOT NULL DEFAULT 'PERLU_DILENGKAPI'
        ");
    }
};