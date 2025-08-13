<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DiamondController;
use App\Http\Controllers\Admin\RingController;
use App\Http\Controllers\Admin\RingStyleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Artisan;
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

    Route::get('/optimize', function () {
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        return 'Command executed successfully!';
    });

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('loginSubmit');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Rings
        Route::resource('/rings', RingController::class)->names('rings');
        // Diamonds
        Route::resource('/diamonds', DiamondController::class)->names('diamonds');

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

        // Ring Width
        Route::get('/ring-width', [RingStyleController::class, 'ringWidth'])->name('ring-width');
        Route::post('/ring-width-store', [RingStyleController::class, 'ringWidthStore'])->name('ring-width-store');
        Route::get('/ring-width-edit/{id}', [RingStyleController::class, 'ringWidthEdit'])->name('ring-width-edit');
        Route::post('/ring-width-update/{id}', [RingStyleController::class, 'ringWidthUpdate'])->name('ring-width-update');
        Route::delete('/ring-width-delete/{id}', [RingStyleController::class, 'ringWidthDelete'])->name('ring-width-delete');

        Route::get('/diamond-shapes', [DiamondController::class, 'diamondShapeList'])->name('diamond-shapes');
        Route::get('/create-shape', [DiamondController::class, 'createDShape'])->name('create-shape');
        Route::post('/store-shape', [DiamondController::class, 'storeDShape'])->name('store-d-shape');
        Route::get('/edit-shape/{id}', [DiamondController::class, 'editDShape'])->name('edit-d-shape');
        Route::post('/update-shape/{id}', [DiamondController::class, 'updateDShape'])->name('update-d-shape');
        Route::delete('/delete-shape/{id}', [DiamondController::class, 'deleteDiamondShape'])->name('delete-d-shape');

        Route::get('/diamond-cuts', [DiamondController::class, 'diamondCutList'])->name('diamond-cuts');
        Route::post('/store-cut', [DiamondController::class, 'storeDiamondCut'])->name('store-d-cut');
        Route::get('/edit-cut/{id}', [DiamondController::class, 'editDiamondCut'])->name('edit-d-cut');
        Route::post('/update-cut/{id}', [DiamondController::class, 'updateDiamondCut'])->name('update-d-cut');
        Route::delete('/delete-cut/{id}', [DiamondController::class, 'deleteDiamondCut'])->name('delete-d-cut');

        Route::get('/diamond-colors', [DiamondController::class, 'diamondColorList'])->name('diamond-colors');
        Route::get('/create-color', [DiamondController::class, 'createDColor'])->name('create-color');
        Route::post('/store-color', [DiamondController::class, 'storeDColor'])->name('store-d-color');
        Route::get('/diamond-color-edit/{id}', [DiamondController::class, 'diamondColorEdit'])->name('diamond-color-edit');
        Route::post('/diamond-color-update/{id}', [DiamondController::class, 'diamondColorUpdate'])->name('diamond-color-update');
        Route::delete('/delete-color/{id}', [DiamondController::class, 'deleteDiamondColor'])->name('delete-d-color');
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
