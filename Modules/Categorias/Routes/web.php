<?php

use Modules\Categorias\Http\Controllers\CategoriasController;
use Illuminate\Support\Facades\Route;
app()->make('router')->aliasMiddleware('permisson', \Spatie\Permission\Middlewares\PermissionMiddleware::class);

Route::middleware('auth')->prefix('admin/categoria')->group(function() {
    Route::controller(CategoriasController::class)->group(function () {
        Route::get('/', 'index')->middleware(['permisson:read categorias'])->name('categorias.index');
        Route::post('/', 'store')->middleware(['permisson:create categorias'])->name('categorias.store');
        Route::post('/show', 'show')->middleware(['permisson:read categorias'])->name('categorias.show');
        Route::put('/', 'update')->middleware(['permisson:update categorias'])->name('categorias.update');
        Route::delete('/', 'destroy')->middleware(['permisson:delete categorias'])->name('categorias.destroy');
    });
});
