<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get organizer's events
        $eventIds = $user->events()->pluck('id');

        // Statistics
        $totalEvents = $user->events()->count();
        $totalTicketsSold = DB::table('booking_details')
            ->join('tickets', 'booking_details.ticket_id', '=', 'tickets.id')
            ->whereIn('tickets.event_id', $eventIds)
            ->join('bookings', 'booking_details.booking_id', '=', 'bookings.id')
            ->where('bookings.status', 'approved')
            ->sum('booking_details.quantity');

        $totalRevenue = DB::table('booking_details')
            ->join('tickets', 'booking_details.ticket_id', '=', 'tickets.id')
            ->whereIn('tickets.event_id', $eventIds)
            ->join('bookings', 'booking_details.booking_id', '=', 'bookings.id')
            ->where('bookings.status', 'approved')
            ->sum('booking_details.subtotal');

        // Recent bookings
        $recentBookings = Booking::with(['user', 'details.ticket.event'])
            ->whereHas('details.ticket.event', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->take(5)
            ->get();

        // Monthly revenue for chart (last 6 months)
        $monthlyRevenue = DB::table('booking_details')
            ->join('tickets', 'booking_details.ticket_id', '=', 'tickets.id')
            ->join('bookings', 'booking_details.booking_id', '=', 'bookings.id')
            ->whereIn('tickets.event_id', $eventIds)
            ->where('bookings.status', 'approved')
            ->where('bookings.created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('MONTH(bookings.created_at) as month'),
                DB::raw('YEAR(bookings.created_at) as year'),
                DB::raw('SUM(booking_details.subtotal) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Ticket sales by event for chart
        $ticketsByEvent = DB::table('booking_details')
            ->join('tickets', 'booking_details.ticket_id', '=', 'tickets.id')
            ->join('events', 'tickets.event_id', '=', 'events.id')
            ->join('bookings', 'booking_details.booking_id', '=', 'bookings.id')
            ->whereIn('tickets.event_id', $eventIds)
            ->where('bookings.status', 'approved')
            ->select(
                'events.name',
                DB::raw('SUM(booking_details.quantity) as total_tickets')
            )
            ->groupBy('events.id', 'events.name')
            ->orderByDesc('total_tickets')
            ->take(5)
            ->get();

        return view('organizer.dashboard', compact(
            'totalEvents',
            'totalTicketsSold',
            'totalRevenue',
            'recentBookings',
            'monthlyRevenue',
            'ticketsByEvent'
        ));
    }

    public function pending()
    {
        return view('organizer.pending');
    }

    public function rejected()
    {
        $user = auth()->user();
        return view('organizer.rejected', compact('user'));
    }

    public function deleteAccount(Request $request)
    {
        $user = auth()->user();

        auth()->logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Account deleted successfully.');
    }
}