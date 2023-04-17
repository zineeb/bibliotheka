<?php

use App\Http\Controllers\UserConnectionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['web'])->group(function () {
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/callback/google', [GoogleAuthController::class, 'handleGoogleCallback']);
});

Route::get('/loginAndRegister', function () {return view('loginAndRegister');});
Route::get('/forgot-password', function () { return view('forgotPassword');});
Route::get('/reset_password', [UserConnectionController::class, 'showResetPasswordForm'])->name('user.reset_password');
