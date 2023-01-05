<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/catalogo_auditorias', function (Request $request) {
    return $request->user();
});