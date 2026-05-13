<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengajuanController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->prefix('auth')->group(function(){
    Route::middleware('guest')->group(function () {
        
        // Tampilkan Form Login
        Route::get('login', 'login_form')->name('login.form');
        
        // Proses Login (POST)
        Route::post('login', 'login')->name('login');
        
    });

    Route::middleware('auth')->group(function(){
        Route::get('logout', 'logout')->name('logout');
    });

});

Route::controller(PengajuanController::class)->group(function(){
    Route::middleware('auth')->group(function(){
        Route::get('pengajuan/{email}/{key}/{file}', 'getFile');
        Route::get('sidang/{email}/{file}', 'getFileSidang');
    });
});
