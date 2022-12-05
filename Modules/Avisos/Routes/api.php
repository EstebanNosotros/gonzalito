<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/avisos', function (Request $request) {
    return $request->user();
});