<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\Auth\LoginController;
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
        // Ring Style
        Route::resource('/ring-style', RingStyleController::class)->names('ring-style');
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
