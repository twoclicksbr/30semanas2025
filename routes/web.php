<?php

use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\SiteHomeController;
use App\Http\Controllers\SiteLoginController;
use App\Http\Controllers\SiteVideoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', [SiteHomeController::class, 'index'])->name('home');
Route::get('/home', [SiteHomeController::class, 'index']);

Route::get('/video', [SiteVideoController::class, 'index']);

Route::get('/login', [SiteLoginController::class, 'index'])->name('login');
Route::post('/login', [SiteLoginController::class, 'login'])->name('login.process');

Route::get('/logout', function () { Session::flush(); return redirect()->route('login'); })->name('logout');


Route::get('/cadastro', function () { return view('register'); })->name('register');


Route::get('/rec_password', [PasswordResetController::class, 'showEmailForm'])->name('password.request');
Route::post('/rec_password', [PasswordResetController::class, 'sendToken'])->name('password.send');

Route::get('/verify_token', [PasswordResetController::class, 'showTokenForm'])->name('password.verify.form');
Route::post('/verify_token', [PasswordResetController::class, 'verifyToken'])->name('password.verify');

Route::post('/reset_password', [PasswordResetController::class, 'resetPassword'])->name('password.update');
Route::get('/reset_password', [PasswordResetController::class, 'formReset'])->name('password.reset');



Route::get('/admin/type_participation', function () { return view('admin.type_participation.type_participation'); });



Route::post('/check-cpf', function (Illuminate\Http\Request $request) {
    $cpf = preg_replace('/\D/', '', $request->cpf);

    $result = \App\Models\Person::where('cpf', $cpf)->get();

    return response()->json([
        'status' => true,
        'result' => [
            'data' => $result
        ]
    ]);
});

Route::post('/check-email', function (Illuminate\Http\Request $request) {
    $email = $request->email;

    $result = \App\Models\PersonUser::where('email', $email)->get();

    return response()->json([
        'status' => true,
        'result' => [
            'data' => $result
        ]
    ]);
});

Route::post('/check-eklesia', function (Illuminate\Http\Request $request) {
    $eklesia = preg_replace('/\D/', '', $request->eklesia);

    $result = \App\Models\Person::where('eklesia', $eklesia)->get();

    return response()->json([
        'status' => true,
        'result' => [
            'data' => $result
        ]
    ]);
});
