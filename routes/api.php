<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PassportAuthController;
use App\Http\Controllers\API\CategoriasController;
use App\Http\Controllers\API\ProductosController;
use App\Http\Controllers\API\BannersController;
//use App\Models\Categoria;
use Illuminate\Support\Facades\Storage;
use Modules\Categorias\Models\Categoria;
use Modules\Productos\Models\Producto;
use Modules\Banners\Models\Banner;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);
  
Route::middleware('auth:api')->group(function () {
    Route::get('get-user', [PassportAuthController::class, 'userInfo']);

    /// Categorias
    
    Route::get('categoriasDownloadImages', [ CategoriasController::class, 'downloadImages' ]);

    Route::get('categoriasHttpGet', [ CategoriasController::class, 'httpGet' ]);

    Route::post('categorias/uploadImages', [ CategoriasController::class, 'uploadImages' ]);
 
    Route::resource('categorias', CategoriasController::class);

    /// Productos

    Route::get('productosDownloadImages', [ ProductosController::class, 'downloadImages' ]);

    Route::get('productosHttpGet', [ ProductosController::class, 'httpGet' ]);

    Route::post('productos/uploadImages', [ ProductosController::class, 'uploadImages' ]);
 
    Route::resource('productos', ProductosController::class);

    /// Banners

    Route::get('bannersDownloadImages', [ BannersController::class, 'downloadImages' ]);

    Route::get('bannersHttpGet', [ BannersController::class, 'httpGet' ]);

    Route::post('banners/uploadImages', [ BannersController::class, 'uploadImages' ]);

    Route::resource('banners', BannersController::class);

});