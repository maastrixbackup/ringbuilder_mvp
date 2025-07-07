<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DiamondController;
use App\Http\Controllers\Admin\RingController;
use App\Http\Controllers\Admin\RingStyleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('loginSubmit');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Rings
        Route::resource('/rings', RingController::class)->names('rings');

        // Ring Style
        Route::resource('/ring-style', RingStyleController::class)->names('ring-style');
        // Ring size
        Route::get('/ring-sizes', [RingStyleController::class, 'ringSize'])->name('ring-sizes');
        Route::post('/ring-size-store', [RingStyleController::class, 'ringSizeStore'])->name('ring-size-store');
        Route::get('/ring-size-edit/{id}', [RingStyleController::class, 'ringSizeEdit'])->name('ring-size-edit');
        Route::post('/ring-size-update/{id}', [RingStyleController::class, 'ringSizeUpdate'])->name('ring-size-update');
        Route::delete('/ring-size-delete/{id}', [RingStyleController::class, 'ringSizeDelete'])->name('ring-size-delete');
        // Jewellery Karat
        Route::get('/jewellery-karats', [RingStyleController::class, 'jewelleryKarat'])->name('jewellery-karats');
        Route::post('/jewellery-karat-store', [RingStyleController::class, 'jewelleryKaratStore'])->name('jewellery-karat-store');
        Route::get('/jewellery-karat-edit/{id}', [RingStyleController::class, 'jewelleryKaratEdit'])->name('jewellery-karat-edit');
        Route::post('/jewellery-karat-update/{id}', [RingStyleController::class, 'jewelleryKaratUpdate'])->name('jewellery-karat-update');
        Route::delete('/jewellery-karat-delete/{id}', [RingStyleController::class, 'jewelleryKaratDelete'])->name('jewellery-karat-delete');
        // Ring Color
        Route::get('/ring-color', [RingStyleController::class, 'ringColor'])->name('ring-color');
        Route::post('/ring-color-store', [RingStyleController::class, 'ringColorStore'])->name('ring-color-store');
        Route::get('/ring-color-edit/{id}', [RingStyleController::class, 'ringColorEdit'])->name('ring-color-edit');
        Route::post('/ring-color-update/{id}', [RingStyleController::class, 'ringColorUpdate'])->name('ring-color-update');
        Route::delete('/ring-color-delete/{id}', [RingStyleController::class, 'ringColorDelete'])->name('ring-color-delete');

        Route::get('/diamond-shapes', [DiamondController::class, 'diamondShapeList'])->name('diamond-shapes');
        Route::get('/create-shape', [DiamondController::class, 'createDShape'])->name('create-shape');
        Route::post('/store-shape', [DiamondController::class, 'storeDShape'])->name('store-d-shape');
    });
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__ . '/auth.php';

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
