<?php

use Modules\Avisos\Http\Controllers\AvisosController;
use Illuminate\Support\Facades\Route;
app()->make('router')->aliasMiddleware('permisson', \Spatie\Permission\Middlewares\PermissionMiddleware::class);

Route::middleware('auth')->prefix('admin/aviso')->group(function() {
    Route::controller(AvisosController::class)->group(function () {
        Route::get('/', 'index')->middleware(['permisson:read avisos'])->name('avisos.index');
        Route::post('/', 'store')->middleware(['permisson:create avisos'])->name('avisos.store');
        Route::post('/show', 'show')->middleware(['permisson:read avisos'])->name('avisos.show');
        Route::put('/', 'update')->middleware(['permisson:update avisos'])->name('avisos.update');
        Route::delete('/', 'destroy')->middleware(['permisson:delete avisos'])->name('avisos.destroy');
    });
});
