<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [AdminController::class, 'login'])->name('login');

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::prefix('setting')->group(function () {
        Route::get('/general-setting', [SettingController::class, 'general_setting'])->name('setting.general');
    });

    Route::prefix('product')->group(function () {
        Route::get('/create', [ProductController::class, 'create'])->name('product.create');
    });

    Route::prefix('brand')->group(function () {
        Route::get('/create', [ProductController::class, 'brand_create'])->name('brand.create');
    });

    Route::prefix('category')->group(function () {
        Route::get('/create', [ProductController::class, 'category_create'])->name('category.create');
    });
});
