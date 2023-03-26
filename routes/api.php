<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserRegisterController;
use App\Http\Controllers\UserConnectionController;
use App\Http\Controllers\BooksController;

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

Route::middleware('api')->group(function () {
    Route::post('/register', [UserRegisterController::class, 'userRegister'])->name('user.register');
    Route::post('/login', [UserConnectionController::class, 'connectRegister'])->name('user.connection');
    Route::post('/forgot_password', [UserConnectionController::class, 'forgotPassword'])->name('user.forget_password');
    Route::get('/reset_password/{token}', [UserConnectionController::class, 'resetPassword'])->name('user.reset_password');

    Route::post('/addcategory', [BooksController::class, 'addCategory'])->name('book.addCategory');
    Route::post('/addbook', [BooksController::class, 'addBook'])->name('book.addBook');
    Route::post('/addreview', [BooksController::class, 'addRewiew'])->name('book.addRewiew');
    Route::post('/deletebook', [BooksController::class, 'deleteBook'])->name('book.deleteBook');
});







