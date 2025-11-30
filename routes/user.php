<?php

use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\FavoriteController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ReviewController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

   // Bookings
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/ticket', [BookingController::class, 'ticket'])->name('bookings.ticket');
    Route::get('/bookings/{booking}/download', [BookingController::class, 'downloadTicket'])->name('bookings.download');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Reviews
    Route::post('/bookings/{booking}/review', [ReviewController::class, 'store'])->name('reviews.store');

    // Favorites
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::delete('/favorites/{favorite}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});