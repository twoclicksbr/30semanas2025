<?php

use App\Http\Controllers\ChurchController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CredentialController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\TypeContactController;
use App\Http\Controllers\TypeParticipationController;
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



        // ✅ Rotas de Group (SEM restrição de ID)
        Route::get('group', [GroupController::class, 'index']);  // Listar todas
        Route::post('group', [GroupController::class, 'store']); // Criar novo gênero

        // ✅ Rotas de group (PRECISAM de {id})
        Route::get('group/{id}', [GroupController::class, 'show']);
        Route::put('group/{id}', [GroupController::class, 'update']);
        Route::delete('group/{id}', [GroupController::class, 'destroy']);

        // ❌ Mensagem de erro apenas para endpoints que precisam de {id}, mas não receberam
        Route::match(['put', 'delete'], 'group', function () {
            return response()->json([
                'error' => 'Invalid Request',
                'details' => 'Enter the {id} in the URL for this action'
            ], 400);
        });



        // ✅ Rotas de Church (SEM restrição de ID)
        Route::get('church', [ChurchController::class, 'index']);  // Listar todas
        Route::post('church', [ChurchController::class, 'store']); // Criar novo gênero

        // ✅ Rotas de church (PRECISAM de {id})
        Route::get('church/{id}', [ChurchController::class, 'show']);
        Route::put('church/{id}', [ChurchController::class, 'update']);
        Route::delete('church/{id}', [ChurchController::class, 'destroy']);

        // ❌ Mensagem de erro apenas para endpoints que precisam de {id}, mas não receberam
        Route::match(['put', 'delete'], 'church', function () {
            return response()->json([
                'error' => 'Invalid Request',
                'details' => 'Enter the {id} in the URL for this action'
            ], 400);
        });



        // ✅ Rotas de Address (SEM restrição de ID)
        Route::get('address', [AddressController::class, 'index']);  // Listar todas
        Route::post('address', [AddressController::class, 'store']); // Criar novo gênero

        // ✅ Rotas de address (PRECISAM de {id})
        Route::get('address/{id}', [AddressController::class, 'show']);
        Route::put('address/{id}', [AddressController::class, 'update']);
        Route::delete('address/{id}', [AddressController::class, 'destroy']);

        // ❌ Mensagem de erro apenas para endpoints que precisam de {id}, mas não receberam
        Route::match(['put', 'delete'], 'address', function () {
            return response()->json([
                'error' => 'Invalid Request',
                'details' => 'Enter the {id} in the URL for this action'
            ], 400);
        });



        // ✅ Rotas de TypeContact (SEM restrição de ID)
        Route::get('type_contact', [TypeContactController::class, 'index']);  // Listar todas
        Route::post('type_contact', [TypeContactController::class, 'store']); // Criar novo gênero

        // ✅ Rotas de type_contact (PRECISAM de {id})
        Route::get('type_contact/{id}', [TypeContactController::class, 'show']);
        Route::put('type_contact/{id}', [TypeContactController::class, 'update']);
        Route::delete('type_contact/{id}', [TypeContactController::class, 'destroy']);

        // ❌ Mensagem de erro apenas para endpoints que precisam de {id}, mas não receberam
        Route::match(['put', 'delete'], 'type_contact', function () {
            return response()->json([
                'error' => 'Invalid Request',
                'details' => 'Enter the {id} in the URL for this action'
            ], 400);
        });



        // ✅ Rotas de Contact (SEM restrição de ID)
        Route::get('contact', [ContactController::class, 'index']);  // Listar todas
        Route::post('contact', [ContactController::class, 'store']); // Criar novo gênero

        // ✅ Rotas de contact (PRECISAM de {id})
        Route::get('contact/{id}', [ContactController::class, 'show']);
        Route::put('contact/{id}', [ContactController::class, 'update']);
        Route::delete('contact/{id}', [ContactController::class, 'destroy']);

        // ❌ Mensagem de erro apenas para endpoints que precisam de {id}, mas não receberam
        Route::match(['put', 'delete'], 'contact', function () {
            return response()->json([
                'error' => 'Invalid Request',
                'details' => 'Enter the {id} in the URL for this action'
            ], 400);
        });



        // ✅ Rotas de TypeParticipation (SEM restrição de ID)
        Route::get('type_participation', [TypeParticipationController::class, 'index']);  // Listar todas
        Route::post('type_participation', [TypeParticipationController::class, 'store']); // Criar novo gênero

        // ✅ Rotas de type_participation (PRECISAM de {id})
        Route::get('type_participation/{id}', [TypeParticipationController::class, 'show']);
        Route::put('type_participation/{id}', [TypeParticipationController::class, 'update']);
        Route::delete('type_participation/{id}', [TypeParticipationController::class, 'destroy']);

        // ❌ Mensagem de erro apenas para endpoints que precisam de {id}, mas não receberam
        Route::match(['put', 'delete'], 'type_participation', function () {
            return response()->json([
                'error' => 'Invalid Request',
                'details' => 'Enter the {id} in the URL for this action'
            ], 400);
        });



        // ✅ Rotas de Person (SEM restrição de ID)
        Route::get('person', [PersonController::class, 'index']);  // Listar todas
        Route::post('person', [PersonController::class, 'store']); // Criar novo gênero

        // ✅ Rotas de person (PRECISAM de {id})
        Route::get('person/{id}', [PersonController::class, 'show']);
        Route::put('person/{id}', [PersonController::class, 'update']);
        Route::delete('person/{id}', [PersonController::class, 'destroy']);

        // ❌ Mensagem de erro apenas para endpoints que precisam de {id}, mas não receberam
        Route::match(['put', 'delete'], 'person', function () {
            return response()->json([
                'error' => 'Invalid Request',
                'details' => 'Enter the {id} in the URL for this action'
            ], 400);
        });

    });

});
