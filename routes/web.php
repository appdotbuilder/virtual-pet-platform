<?php

use App\Http\Controllers\VirtualPetController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

Route::get('/', [VirtualPetController::class, 'index'])->name('home');
Route::post('/adopt-pet', [VirtualPetController::class, 'store'])->name('pets.adopt')->middleware('auth');
Route::patch('/user-pets/{userPet}', [VirtualPetController::class, 'update'])->name('pets.update')->middleware('auth');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
