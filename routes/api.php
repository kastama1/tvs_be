<?php

use App\Http\Controllers\ElectionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum'])->group(static function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('/elections')->group(static function () {
        Route::get('/', [ElectionController::class, 'index']);
        Route::get('/list-by-type', [ElectionController::class, 'listByType']);
        Route::get('/{election}', [ElectionController::class, 'show']);
        Route::post('/', [ElectionController::class, 'store']);
        Route::put('/{election}', [ElectionController::class, 'update'])->whereNumber('lead');
    });
});

require __DIR__.'/auth.php';
