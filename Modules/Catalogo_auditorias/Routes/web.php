<?php

use Modules\Catalogo_auditorias\Http\Controllers\Catalogo_auditoriasController;
use Illuminate\Support\Facades\Route;
app()->make('router')->aliasMiddleware('permisson', \Spatie\Permission\Middlewares\PermissionMiddleware::class);

Route::middleware('auth')->prefix('admin/catalogo_auditoria')->group(function() {
    Route::controller(Catalogo_auditoriasController::class)->group(function () {
        Route::get('/', 'index')->middleware(['permisson:read catalogo_auditorias'])->name('catalogo_auditorias.index');
        Route::post('/', 'store')->middleware(['permisson:create catalogo_auditorias'])->name('catalogo_auditorias.store');
        Route::post('/show', 'show')->middleware(['permisson:read catalogo_auditorias'])->name('catalogo_auditorias.show');
        Route::put('/', 'update')->middleware(['permisson:update catalogo_auditorias'])->name('catalogo_auditorias.update');
        Route::delete('/', 'destroy')->middleware(['permisson:delete catalogo_auditorias'])->name('catalogo_auditorias.destroy');

        Route::get('/getCatalogoAuditorias', 'getCatalogoAuditorias')->middleware(['permisson:read catalogo_auditorias'])->name('getCatalogoAuditorias');
        // Route::get('/consulta', 'consulta')->middleware(['permisson:read catalogo_auditorias'])->name('catalogo_auditorias.consulta');
    });
});
