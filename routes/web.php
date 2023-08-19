<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Payment\TripayCallbackController;
use Illuminate\Support\Facades\Artisan;

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

// Route::get('/', [HomeController::class, 'index'])->name('home');
// Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::redirect('/home', '/');
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('products/{type}', [ProductController::class, 'index'])->name('products');
Route::get('product/{product}', [ProductController::class, 'show'])->name('product.show');

Route::group(['middleware' => 'auth'], function () {
    Route::get('product/{product}/checkout', [ProductController::class, 'checkout'])->name('product.checkout');

    Route::post('order', [OrderController::class, 'store'])->name('order.store');
    Route::get('order/{reference}', [OrderController::class, 'show'])->name('order.show');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/clearcache', function () {
        Artisan::call('cache:clear');
        Artisan::call('config:cache');
        Artisan::call('view:clear');
        return "Cleared!";
    });
});

Route::post('callback', [TripayCallbackController::class, 'handle']);

require __DIR__ . '/auth.php';
