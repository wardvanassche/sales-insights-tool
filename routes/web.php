<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified'])
    ->get('/', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/quickscan', function () {
    return Inertia::render('Quickscan');
})->middleware(['auth', 'verified'])->name('quickscan');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
