<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManifestController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/book', [BookController::class, 'index'])->name('book');
Route::get('/book/confirm/{booking}', [BookController::class, 'confirm'])->name('book.confirm');
Route::get('/book/{leg}', [BookController::class, 'show'])->name('book.show');
Route::post('/book/{leg}', [BookController::class, 'store'])->name('book.store');

Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::get('/check-in', [CheckInController::class, 'index'])->name('check-in.index');
    Route::get('/check-in/{leg}', [CheckInController::class, 'show'])->name('check-in.show');
    Route::post('/check-in/passenger/{passenger}', [CheckInController::class, 'store'])->name('check-in.store');
    Route::get('/manifest', [ManifestController::class, 'index'])->name('manifest.index');
    Route::post('/manifest/download', [ManifestController::class, 'download'])->name('manifest.download');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
