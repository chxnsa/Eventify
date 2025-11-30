<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\User\FavoriteController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Home
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Events
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/category/{slug}', [EventController::class, 'byCategory'])->name('events.category');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');
Route::get('/search', [EventController::class, 'search'])->name('search');

// Favorite toggle - untuk semua authenticated user
Route::middleware('auth')->post('/favorites/{event}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

Route::get('/bookings/{booking}/download', [BookingController::class, 'downloadTicket'])->name('bookings.download');

// routes/web.php
Route::get('/', function () {
    $featuredEvents = \App\Models\Event::with(['category', 'tickets'])
        ->published()
        ->featured()
        ->upcoming()
        ->take(5)
        ->get();

    $categories = \App\Models\Category::all();

    return view('landing', compact('featuredEvents', 'categories'));
})->name('landing');

// Include route files
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/organizer.php';
require __DIR__.'/user.php';