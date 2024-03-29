<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
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

    Route::post('/reset_password', [UserConnectionController::class, 'resetPassword'])->name('api.reset_password');
    Route::post('/contact', [UserConnectionController::class, 'sendEmail'])->name('api.send_email');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout',[UserConnectionController::class, 'logout'])->name('user.logout');
        Route::get('/dashboard_data', [DashboardController::class, 'dashboardData']);
        Route::get('/search-books', [BooksController::class, 'searchBooks']);
        Route::post('/addBook', [BooksController::class, 'addBook'])->name('book.addBook');
        Route::post('/updateBook', [BooksController::class, 'updateBookData'])->name('book.updateBookData');
        Route::post('/deletebook', [BooksController::class, 'deleteBook'])->name('book.deleteBook');
        Route::get('user/{id}', [UserController::class, 'show']);
        Route::put('user/{id}', [UserController::class, 'update']);
        Route::post('/update-profile-image/{id}', [UserController::class, 'updateProfileImage']);
        Route::delete('user/{id}', [UserController::class, 'destroy']);
    });
});



