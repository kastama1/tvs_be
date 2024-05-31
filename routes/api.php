<?php

use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\ElectionPartyController;
use App\Http\Controllers\FileController;
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
        Route::get('/{election}', [ElectionController::class, 'show']);
        Route::post('/', [ElectionController::class, 'store']);
        Route::put('/{election}', [ElectionController::class, 'update']);
        Route::get('/{election}/vote', [ElectionController::class, 'showVote']);
        Route::post('/{election}/vote', [ElectionController::class, 'vote']);
        Route::put('/{election}/assign-election-parties', [ElectionController::class, 'assignElectionParties']);
        Route::put('/{election}/assign-candidates', [ElectionController::class, 'assignCandidates']);
    });

    Route::prefix('/election-parties')->group(static function () {
        Route::get('/', [ElectionPartyController::class, 'index']);
        Route::get('/{election_party}', [ElectionPartyController::class, 'show']);
        Route::post('/', [ElectionPartyController::class, 'store']);
        Route::put('/{election_party}', [ElectionPartyController::class, 'update']);
    });

    Route::prefix('/candidates')->group(static function () {
        Route::get('/', [CandidateController::class, 'index']);
        Route::get('/{candidate}', [CandidateController::class, 'show']);
        Route::post('/', [CandidateController::class, 'store']);
        Route::put('/{candidate}', [CandidateController::class, 'update']);
    });

    Route::prefix('/files')->group(static function () {
        Route::delete('/{file}', [FileController::class, 'destroy']);
    });
});

require __DIR__.'/auth.php';
