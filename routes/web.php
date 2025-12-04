<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/quickscan', function () {
    return Inertia::render('Quickscan');
})->middleware(['auth', 'verified'])->name('quickscan');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
