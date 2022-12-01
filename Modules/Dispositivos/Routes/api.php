<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/dispositivos', function (Request $request) {
    return $request->user();
});