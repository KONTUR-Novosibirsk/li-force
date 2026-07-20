<?php

use Illuminate\Support\Facades\Route;
use Modules\Services\App\Controllers\Admin\ServiceController;

Route::group(['prefix' => 'admin/services', 'middleware' => ['web', 'dashboard', 'module:services']], function () {
    Route::get('/index', [ServiceController::class, 'index'])->name('admin.services.index');
    Route::get('/show/{service}', [ServiceController::class, 'show'])->name('admin.services.show');
    Route::get('/create', [ServiceController::class, 'create'])->name('admin.services.create');
    Route::post('/store', [ServiceController::class, 'store'])->name('admin.services.store');
    Route::get('/edit/{service}', [ServiceController::class, 'edit'])->name('admin.services.edit');
    Route::patch('/update/{service}', [ServiceController::class, 'update'])->name('admin.services.update');
    Route::delete('/delete/{service}', [ServiceController::class, 'destroy'])->name('admin.services.delete');
    Route::post('/uploadPreview/{service?}', [ServiceController::class, 'uploadPreview'])->name('admin.services.uploadPreview');
    Route::post('/{service}/update-sort', [ServiceController::class, 'updateSort'])->name('admin.services.updateSort');
    Route::post('/getServicesSort', [ServiceController::class, 'getServicesSort'])->name('admin.services.getServicesSort');
});

Route::group(['prefix' => 'service', 'middleware' => ['web', 'module:services']], function () {
    Route::get('/index', [\Modules\Services\App\Controllers\Public\ServiceController::class, 'index'])->name('services.index');
    Route::get('/{alias}', [\Modules\Services\App\Controllers\Public\ServiceController::class, 'show'])->name('services.show');
});
