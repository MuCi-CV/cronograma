<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemImageController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\TrackerController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/tracker');
Route::get('/tracker', [TrackerController::class, 'visual'])->name('tracker.visual');
Route::get('/tracker/detalle', [TrackerController::class, 'detail'])->name('tracker.detail');

Route::middleware('auth')->group(function () {
    Route::patch('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::post('/sections/{section}/items', [ItemController::class, 'store'])->name('items.store');
    Route::post('/stages/{stage}/sections', [SectionController::class, 'store'])->name('sections.store');
    Route::post('/items/{item}/images', [ItemImageController::class, 'store'])->name('images.store');
    Route::delete('/items/{item}/images/{image}', [ItemImageController::class, 'destroy'])->name('images.destroy');
});

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');
Route::post('/auth/logout', [GoogleController::class, 'logout'])->name('auth.logout');
