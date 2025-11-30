<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalUsers = User::where('role', 'user')->count();
        $totalOrganizers = User::where('role', 'organizer')->count();
        $totalEvents = Event::count();
        $totalRevenue = Booking::where('status', 'approved')->sum('total_amount');

        // Pending organizers
        $pendingOrganizers = User::where('role', 'organizer')
            ->where('organizer_status', 'pending')
            ->count();

        // Recent bookings
        $recentBookings = Booking::with(['user', 'details.ticket.event'])
            ->latest()
            ->take(5)
            ->get();

        // Monthly revenue for chart (last 6 months)
        $monthlyRevenue = Booking::where('status', 'approved')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Users registration by month
        $userRegistrations = User::where('role', 'user')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalOrganizers',
            'totalEvents',
            'totalRevenue',
            'pendingOrganizers',
            'recentBookings',
            'monthlyRevenue',
            'userRegistrations'
        ));
    }
}