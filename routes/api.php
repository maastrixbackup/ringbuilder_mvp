<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/ping', function (Request $request) {
    return response()->json([
        'status' => 'success',
        'message' => 'API is working 🚀',
        'data'=> $request->all()
    ]);
});

Route::any('ring-products', [ApiController::class, 'ringProducts']);
Route::any('diamond-products', [ApiController::class, 'diamondProducts']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
