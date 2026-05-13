<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('review_pengajuans', function (Blueprint $table) {
            $table->string('status')->default('revisi')->after('key');
            $table->string('reviewer_type')->default('kepegawaian')->after('status');
        });

        DB::table('review_pengajuans')->where('is_verified', 1)->update(['status' => 'approve']);
        DB::table('review_pengajuans')->where('is_verified', 0)->update(['status' => 'revisi']);

        Schema::table('review_pengajuans', function (Blueprint $table) {
            $table->dropColumn('is_verified');
        });
    }

    public function down(): void
    {
        Schema::table('review_pengajuans', function (Blueprint $table) {
            $table->boolean('is_verified')->default(false);
        });

        DB::table('review_pengajuans')->where('status', 'approve')->update(['is_verified' => 1]);
        DB::table('review_pengajuans')->where('status', '!=', 'approve')->update(['is_verified' => 0]);

        Schema::table('review_pengajuans', function (Blueprint $table) {
            $table->dropColumn(['status', 'reviewer_type']);
        });
    }
};