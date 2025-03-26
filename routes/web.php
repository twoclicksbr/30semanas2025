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




Route::get('/rec_password', [PasswordResetController::class, 'showEmailForm'])->name('password.request');
Route::post('/rec_password', [PasswordResetController::class, 'sendToken'])->name('password.send');

Route::get('/verify_token', [PasswordResetController::class, 'showTokenForm'])->name('password.verify.form');
Route::post('/verify_token', [PasswordResetController::class, 'verifyToken'])->name('password.verify');

Route::post('/reset_password', [PasswordResetController::class, 'resetPassword'])->name('password.update');
Route::get('/reset_password', [PasswordResetController::class, 'formReset'])->name('password.reset');


