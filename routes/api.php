<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GenderController;
use App\Http\Middleware\VerifyHeaders;

Route::prefix('v1')->group(function () {
    
    Route::middleware([VerifyHeaders::class])->group(function () {

        // ✅ Rotas de Credential (SEM restrição de ID)
        Route::get('credential', [AuthController::class, 'index']);  // Listar todas
        Route::post('credential', [AuthController::class, 'store']); // Criar nova credencial

        // ✅ Rotas de Credential (PRECISAM de {id})
        Route::get('credential/{id}', [AuthController::class, 'show']);
        Route::put('credential/{id}', [AuthController::class, 'update']);
        Route::delete('credential/{id}', [AuthController::class, 'destroy']);

        // ❌ Mensagem de erro apenas para endpoints que precisam de {id}, mas não receberam
        Route::match(['put', 'delete'], 'credential', function () {
            return response()->json([
                'error' => 'Invalid Request',
                'details' => 'Enter the {id} in the URL for this action'
            ], 400);
        });

        // ✅ Rotas de Gender (SEM restrição de ID)
        Route::get('gender', [GenderController::class, 'index']);  // Listar todas
        Route::post('gender', [GenderController::class, 'store']); // Criar novo gênero

        // ✅ Rotas de Gender (PRECISAM de {id})
        Route::get('gender/{id}', [GenderController::class, 'show']);
        Route::put('gender/{id}', [GenderController::class, 'update']);
        Route::delete('gender/{id}', [GenderController::class, 'destroy']);

        // ❌ Mensagem de erro apenas para endpoints que precisam de {id}, mas não receberam
        Route::match(['put', 'delete'], 'gender', function () {
            return response()->json([
                'error' => 'Invalid Request',
                'details' => 'Enter the {id} in the URL for this action'
            ], 400);
        });

    });

});
