<?php

use Modules\Vendedores\Http\Controllers\VendedoresController;
use Illuminate\Support\Facades\Route;
app()->make('router')->aliasMiddleware('permisson', \Spatie\Permission\Middlewares\PermissionMiddleware::class);

Route::middleware('auth')->prefix('admin/vendedor')->group(function() {
    Route::controller(VendedoresController::class)->group(function () {
        Route::get('/', 'index')->middleware(['permisson:read vendedores'])->name('vendedores.index');
        Route::post('/', 'store')->middleware(['permisson:create vendedores'])->name('vendedores.store');
        Route::post('/show', 'show')->middleware(['permisson:read vendedores'])->name('vendedores.show');
        Route::put('/', 'update')->middleware(['permisson:update vendedores'])->name('vendedores.update');
        Route::delete('/', 'destroy')->middleware(['permisson:delete vendedores'])->name('vendedores.destroy');
    });
});
