<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $upcomingBookings = $user->bookings()
            ->with(['details.ticket.event'])
            ->where('status', 'approved')
            ->whereHas('details.ticket.event', function ($q) {
                $q->where('date_start', '>=', now());
            })
            ->latest()
            ->take(3)
            ->get();

        $totalBookings = $user->bookings()->count();
        $totalFavorites = $user->favorites()->count();

        return view('user.dashboard', compact('upcomingBookings', 'totalBookings', 'totalFavorites'));
    }
}