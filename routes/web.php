<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HunianController;
use App\Http\Controllers\RumahController;
use App\Http\Controllers\WargaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::middleware('guest')->group(function(){
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function() {
    Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('warga', WargaController::class);
    Route::get('/Warga', [WargaController::class, 'index'])->name('warga');

    Route::resource('hunian', HunianController::class);
    Route::resource('rumah', RumahController::class);
});


