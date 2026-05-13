<?php

use App\Http\Controllers\ApiIntegrationController;
use Illuminate\Support\Facades\Route;

Route::prefix('api-integration')->group(function(){

    Route::controller(ApiIntegrationController::class)->group(function(){
        Route::get('integration/{page}', 'integration')->name('integration');
    });
    
});