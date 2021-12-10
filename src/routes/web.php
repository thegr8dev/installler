<?php

use Illuminate\Support\Facades\Route;
use Mediacity\Installer\Http\Controllers\InstallerController;

Route::group(['prefix' => 'install','middleware' => ['web']],function(){

    Route::get('/eula',[InstallerController::class,'eula'])->name('eulaterm');
    Route::post('/eula',[InstallerController::class,'agreeeula']);

    Route::get('/servercheck',[InstallerController::class,'servercheck']);
    Route::post('/servercheck',[InstallerController::class,'submitservercheck']);

    Route::get('/dbsetup',[InstallerController::class,'dbsetup']);
    Route::post('/dbsetup',[InstallerController::class,'submitdbsetup']);

    Route::get('/verify-license',[InstallerController::class,'license']);
    Route::post('/verify-license',[InstallerController::class,'verifylicense']);

    Route::get('/finish',[InstallerController::class,'finish']);

});
