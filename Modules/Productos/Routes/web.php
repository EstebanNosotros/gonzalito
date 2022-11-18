<?php

use Modules\Productos\Http\Controllers\ProductosController;
use Illuminate\Support\Facades\Route;
app()->make('router')->aliasMiddleware('permisson', \Spatie\Permission\Middlewares\PermissionMiddleware::class);

Route::middleware('auth')->prefix('admin/producto')->group(function() {
    Route::controller(ProductosController::class)->group(function () {
        Route::get('/', 'index')->middleware(['permisson:read productos'])->name('productos.index');
       // Route::post('/', 'store')->middleware(['permisson:create productos'])->name('productos.store');
        Route::post('/show', 'show')->middleware(['permisson:read productos'])->name('productos.show');
        Route::put('/', 'update')->middleware(['permisson:update productos'])->name('productos.update');
        Route::delete('/', 'destroy')->middleware(['permisson:delete productos'])->name('productos.destroy');

        Route::post('/synchronize', 'synchronize')->middleware(['permisson:create productos'])->name('productos.synchronize');
        Route::get('/getProductos', 'getProductos')->middleware(['permisson:read productos'])->name('getProductos');
    });
});
