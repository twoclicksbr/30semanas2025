<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CredentialController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\TypeUserController;
use App\Http\Middleware\VerifyHeaders;

Route::prefix('v1')->group(function () {
    
    Route::middleware([VerifyHeaders::class])->group(function () {

        // ✅ Rotas de Credential (SEM restrição de ID)
        Route::get('credential', [CredentialController::class, 'index']);  // Listar todas
        Route::post('credential', [CredentialController::class, 'store']); // Criar nova credencial

        // ✅ Rotas de Credential (PRECISAM de {id})
        Route::get('credential/{id}', [CredentialController::class, 'show']);
        Route::put('credential/{id}', [CredentialController::class, 'update']);
        Route::delete('credential/{id}', [CredentialController::class, 'destroy']);

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

        // ✅ Rotas de TypeUser (SEM restrição de ID)
        Route::get('type_user', [TypeUserController::class, 'index']);  // Listar todas
        Route::post('type_user', [TypeUserController::class, 'store']); // Criar novo gênero

        // ✅ Rotas de TypeUser (PRECISAM de {id})
        Route::get('type_user/{id}', [TypeUserController::class, 'show']);
        Route::put('type_user/{id}', [TypeUserController::class, 'update']);
        Route::delete('type_user/{id}', [TypeUserController::class, 'destroy']);

        // ❌ Mensagem de erro apenas para endpoints que precisam de {id}, mas não receberam
        Route::match(['put', 'delete'], 'type_user', function () {
            return response()->json([
                'error' => 'Invalid Request',
                'details' => 'Enter the {id} in the URL for this action'
            ], 400);
        });

    });

});
