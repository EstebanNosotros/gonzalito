<?php

use Modules\Dispositivos\Http\Controllers\DispositivosController;
use Illuminate\Support\Facades\Route;
app()->make('router')->aliasMiddleware('permisson', \Spatie\Permission\Middlewares\PermissionMiddleware::class);

Route::middleware('auth')->prefix('admin/dispositivo')->group(function() {
    Route::controller(DispositivosController::class)->group(function () {
        Route::get('/', 'index')->middleware(['permisson:read dispositivos'])->name('dispositivos.index');
        Route::post('/', 'store')->middleware(['permisson:create dispositivos'])->name('dispositivos.store');
        Route::post('/show', 'show')->middleware(['permisson:read dispositivos'])->name('dispositivos.show');
        Route::put('/', 'update')->middleware(['permisson:update dispositivos'])->name('dispositivos.update');
        Route::delete('/', 'destroy')->middleware(['permisson:delete dispositivos'])->name('dispositivos.destroy');
    });
});
