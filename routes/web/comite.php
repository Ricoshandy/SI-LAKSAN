<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\SidangPengajuanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:comite'])->prefix('comite')->group(function(){

    Route::controller(DashboardController::class)->group(function(){
        Route::get('dashboard', 'comite_dashboard')->name('comite_dashboard');
    });

    Route::controller(PengajuanController::class)->group(function(){
        Route::get('pengajuan/list', 'comite_pengajuan_list')->name('comite.pengajuan.list');
        Route::get('pengajuan/sidang/komite/{id}', 'sidang_komite_view')->name('sidang.comite.view');
        Route::get('pengajuan/view/{id}', 'comite_pengajuan_view')->name('comite.pengajuan.view');
        Route::get('pengajuan/file/{id}/{key}', 'comite_serve_file')->name('comite.pengajuan.file');
        Route::get('pengajuan/download/{id}', 'comite_download_berkas')->name('comite.pengajuan.download');
    });

    Route::controller(SidangPengajuanController::class)->group(function(){
        Route::post('pengajuan/sidang/{pengajuanId}/komite', 'action_sidang_komite')->name('action.sidang.komite');
    });
    
});