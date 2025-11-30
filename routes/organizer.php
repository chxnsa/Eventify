<?php

use App\Http\Controllers\Organizer\BookingController;
use App\Http\Controllers\Organizer\DashboardController;
use App\Http\Controllers\Organizer\EventController;
use App\Http\Controllers\Organizer\TicketController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:organizer'])->prefix('organizer')->name('organizer.')->group(function () {
    // Pending & Rejected pages (no approved middleware)
    Route::get('/pending', [DashboardController::class, 'pending'])->name('pending');
    Route::get('/rejected', [DashboardController::class, 'rejected'])->name('rejected');
    Route::delete('/delete-account', [DashboardController::class, 'deleteAccount'])->name('delete-account');

    // Routes that require approved status
    Route::middleware(['approved.organizer'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Events
        Route::resource('events', EventController::class);

        // Tickets
        Route::get('/events/{event}/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
        Route::post('/events/{event}/tickets', [TicketController::class, 'store'])->name('tickets.store');
        Route::get('/events/{event}/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
        Route::put('/events/{event}/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
        Route::delete('/events/{event}/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');

        // Bookings
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::patch('/bookings/{booking}/approve', [BookingController::class, 'approve'])->name('bookings.approve');
        Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    });
});