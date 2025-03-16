<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\VerifyHeaders;

// Route::delete('credential', function () {
//     return response()->json([
//         'error' => 'Invalid Request',
//         'details' => 'Enter the {id} for deletion in the url'
//     ], 400);
// });

Route::prefix('v1/auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);

    Route::middleware([VerifyHeaders::class])->group(function () {
        Route::get('credential', [AuthController::class, 'index']);
        Route::get('credential/{id}', [AuthController::class, 'show']);
        Route::put('credential/{id}', [AuthController::class, 'update']);
        Route::delete('credential/{id}', [AuthController::class, 'destroy']);
        Route::post('credential', [AuthController::class, 'store']);
    });
});
