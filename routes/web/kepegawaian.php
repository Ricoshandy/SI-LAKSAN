<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\ReviewPengajuanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:kepegawaian'])->prefix('kepegawaian')->group(function(){

    Route::controller(DashboardController::class)->group(function(){
        Route::get('dashboard', 'kepegawaian_dashboard')->name('kepegawaian_dashboard');
    });

    Route::controller(PengajuanController::class)->group(function(){
        Route::get('pengajuan/list', 'kepegawaian_pengajuan_list')->name('kepegawaian.pengajuan.list');
        Route::get('pengajuan/review/{id}', 'pengajuan_review')->name('pengajuan.review');
        Route::get('pengajuan/file/{id}/{key}', 'serveFile')->name('kepegawaian.pengajuan.file');
        Route::get('pengajuan/sister/{id}', 'pengajuan_sister')->name('pengajuan.sister');
        Route::get('pengajuan/download/{id_pengajuan}', 'download_pengajuan')->name('download.pengajuan');
        Route::post('pengajuan/approved/{id_pengajuan}', 'kepegawaian_pengajuan_approved')->name('kepegawaian.pengajuan.approved');
        Route::post('pengajuan/rejected/{id_pengajuan}', 'kepegawaian_pengajuan_rejected')->name('kepegawaian.pengajuan.rejected');
    });

    Route::controller(ReviewPengajuanController::class)->group(function(){
        Route::post('pengajuan/review/{pengajuanId}/action', 'action_review')->name('action.review');
    });

    Route::controller(PeriodeController::class)->group(function(){
        Route::get('periode/list', 'kepegawaian_periode_list')->name('kepegawaian.periode.list');
        Route::get('periode/{id}/pengajuan', 'viewPengajuan')->name('kepegawaian.periode.view');
        Route::post('periode/add', 'add')->name('periode.add');
        Route::post('periode/add/{id}', 'edit')->name('periode.edit');
        Route::get('periode/delete/{id}', 'delete')->name('periode.delete');
    });
    
});