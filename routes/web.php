<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('checklogin');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registeruser'])->name('registeruser');
    Route::get('/google-login', [AuthController::class, 'google_login'])->name('google-login');
    Route::get('/googleauth', [AuthController::class, 'googleAuthCallback'])->name('googleauth');

    Route::middleware('auth')->group(function() {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/products', [ProductController::class, 'index'])->name('admin.products');
    });
});
