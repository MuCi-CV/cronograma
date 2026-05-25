<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\TrackerController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/tracker');
Route::get('/tracker', [TrackerController::class, 'visual'])->name('tracker.visual');
Route::get('/tracker/detalle', [TrackerController::class, 'detail'])->name('tracker.detail');

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');
Route::post('/auth/logout', [GoogleController::class, 'logout'])->name('auth.logout');
