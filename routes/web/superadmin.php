<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->group(function(){

    Route::controller(DashboardController::class)->group(function(){
        Route::get('dashboard', 'superadmin_dashboard')->name('superadmin_dashboard');
    });
    
});