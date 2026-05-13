<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\SidangPengajuanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'senat'])->prefix('senat')->group(function(){

    Route::controller(DashboardController::class)->group(function(){
        Route::get('dashboard', 'senat_dashboard')->name('senat_dashboard');
    });
    
  Route::controller(PengajuanController::class)->group(function(){
    Route::get('pengajuan/list', 'senat_pengajuan_list')->name('senat.pengajuan.list');
    Route::get('pengajuan/view/{id}', 'pengajuan_view_senat')->name('senat.pengajuan.view');
   Route::get('pengajuan/detail/{id}', 'pengajuan_view_senat_detail')->name('senat.pengajuan.detail');
    Route::get('pengajuan/sidang/komite/{id}', 'sidang_senat_view')->name('sidang.senat.view');
    Route::get('pengajuan/file/{id}/{key}', 'senat_get_file')->name('senat.pengajuan.file');
    Route::get('pengajuan/download/{id}', 'senat_download_pengajuan')->name('senat.pengajuan.download');
});

    Route::controller(SidangPengajuanController::class)->group(function(){
        Route::post('pengajuan/sidang/{pengajuanId}/komite', 'action_sidang_senat')->name('action.sidang.senat');
    });
});