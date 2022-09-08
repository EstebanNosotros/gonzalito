<?php

use Modules\Banners\Http\Controllers\BannersController;
use Illuminate\Support\Facades\Route;
app()->make('router')->aliasMiddleware('permisson', \Spatie\Permission\Middlewares\PermissionMiddleware::class);

Route::middleware('auth')->prefix('admin/banner')->group(function() {
    Route::controller(BannersController::class)->group(function () {
        Route::get('/', 'index')->middleware(['permisson:read banners'])->name('banners.index');
        Route::post('/', 'store')->middleware(['permisson:create banners'])->name('banners.store');
        Route::post('/show', 'show')->middleware(['permisson:read banners'])->name('banners.show');
        Route::put('/', 'update')->middleware(['permisson:update banners'])->name('banners.update');
        Route::delete('/', 'destroy')->middleware(['permisson:delete banners'])->name('banners.destroy');
    });
});
