<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/vendedores', function (Request $request) {
    return $request->user();
});