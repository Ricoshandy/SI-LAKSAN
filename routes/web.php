<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pengajuan/{email}/{key}/{file}', [PengajuanController::class, 'getFile'])
    ->name('pengajuan.file');

Route::get('/sidang/{email}/{file}', [PengajuanController::class, 'getFileSidang'])
    ->name('sidang.file');

// Route::get('/', function () {
//     return view('dashboard');
// });
