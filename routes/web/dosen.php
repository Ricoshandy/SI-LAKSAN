<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormPengajuanController;
use App\Http\Controllers\PengajuanController;

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'dosen'])->prefix('dosen')->group(function(){

    // Route Untuk Mengarahkan User dengan Role Dosen ke Dashboard Dosen
    Route::controller(DashboardController::class)->group(function(){
        Route::get('dashboard', 'dosen_dashboard')->name('dosen_dashboard');
    });

    Route::controller(FormPengajuanController::class)->group(function(){
        Route::get('pengajuan/form', 'pengajuan_form')->name('pengajuan.form');
        Route::get('pengajuan/form/{id}', 'form')->name('form');
        Route::get('pengajuan/list', 'pengajuan_list')->name('pengajuan.list');
    });
    
    Route::controller(PengajuanController::class)->group(function(){
        Route::post('pengajuan/form/{id}/submit', 'submit')->name('pengajuan.submit');
        Route::get('pengajuan/view/{id}', 'pengajuan_view')->name('pengajuan.view');
        Route::get(uri: 'pengajuan/file/{id}/{key}', action: 'serveFile')->name(name: 'pengajuan.file');
        Route::get('pengajuan/edit/{id}', 'pengajuan_edit')->name('pengajuan.edit');
        Route::post('pengajuan/edit/{id}/submit', 'pengajuan_edit_submit')->name('pengajuan.edit.submit');
        Route::get('pengajuan/progress/{id}', 'pengajuan_progress')->name('pengajuan.progress');
        Route::get('sk/download/{id_pengajuan}', 'download_sk')->name('sk.download');

    });
    
});
