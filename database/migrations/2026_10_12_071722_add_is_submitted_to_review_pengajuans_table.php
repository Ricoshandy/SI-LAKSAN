<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan penanda draft atau hasil review final.
     */
    public function up(): void
    {
        Schema::table('review_pengajuans', function (Blueprint $table) {
            $table
                ->boolean('is_submitted')
                ->default(true)
                ->after('reviewer_type');
        });
    }

    /**
     * Menghapus kolom jika migration dibatalkan.
     */
    public function down(): void
    {
        Schema::table('review_pengajuans', function (Blueprint $table) {
            $table->dropColumn('is_submitted');
        });
    }
};