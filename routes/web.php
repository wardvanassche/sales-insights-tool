<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuickscanController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified'])
    ->get('/', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/quickscan', function () {
    return Inertia::render('Quickscan');
})->middleware(['auth', 'verified'])->name('quickscan');

Route::post('/quickscan', QuickscanController::class)
    ->name('quickscan.store');

// Test CompanyInfo client
Route::get('/debug/companyinfo/search', function (
    \App\Services\CompanyInfo\CompanyInfoClient $client
) {
    return $client->search(
        request('name'),
        request('url')
    );
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
