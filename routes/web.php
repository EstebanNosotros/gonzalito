<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('index');

Auth::routes([
    'register'  => false,
    'reset'     => false,
    'confirm'   => false
]);

Route::middleware(['auth'])->get('/home', [DashboardController::class, 'index'])->name('home');

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::controller(UserController::class)->group(function () {
        Route::get('user', 'index')->middleware(['permission:read user'])->name('user.index');
        Route::post('user', 'store')->middleware(['permission:create user'])->name('user.store');
        Route::post('user/show', 'show')->middleware(['permission:read user'])->name('user.show');
        Route::put('user', 'update')->middleware(['permission:update user'])->name('user.update');
        Route::delete('user', 'destroy')->middleware(['permission:delete user'])->name('user.destroy');
    });
});
