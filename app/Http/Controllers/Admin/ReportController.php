<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Date range filter
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Summary statistics
        $totalSales = Booking::where('status', 'approved')
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->sum('total_amount');

        $totalBookings = Booking::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->count();

        $approvedBookings = Booking::where('status', 'approved')
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->count();

        $cancelledBookings = Booking::where('status', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->count();

        $totalTicketsSold = DB::table('booking_details')
            ->join('bookings', 'booking_details.booking_id', '=', 'bookings.id')
            ->where('bookings.status', 'approved')
            ->whereBetween('bookings.created_at', [$startDate, $endDate . ' 23:59:59'])
            ->sum('booking_details.quantity');

        // Sales by event
        $salesByEvent = DB::table('booking_details')
            ->join('bookings', 'booking_details.booking_id', '=', 'bookings.id')
            ->join('tickets', 'booking_details.ticket_id', '=', 'tickets.id')
            ->join('events', 'tickets.event_id', '=', 'events.id')
            ->where('bookings.status', 'approved')
            ->whereBetween('bookings.created_at', [$startDate, $endDate . ' 23:59:59'])
            ->select(
                'events.id',
                'events.name',
                DB::raw('SUM(booking_details.quantity) as tickets_sold'),
                DB::raw('SUM(booking_details.subtotal) as total_revenue')
            )
            ->groupBy('events.id', 'events.name')
            ->orderByDesc('total_revenue')
            ->get();

        // Daily sales for chart
        $dailySales = Booking::where('status', 'approved')
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Booking status breakdown
        $bookingStatus = Booking::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Recent bookings
        $recentBookings = Booking::with(['user', 'details.ticket.event'])
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.reports.index', compact(
            'startDate',
            'endDate',
            'totalSales',
            'totalBookings',
            'approvedBookings',
            'cancelledBookings',
            'totalTicketsSold',
            'salesByEvent',
            'dailySales',
            'bookingStatus',
            'recentBookings'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $bookings = Booking::with(['user', 'details.ticket.event'])
            ->where('status', 'approved')
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->get();

        // Generate CSV
        $filename = 'sales_report_' . $startDate . '_to_' . $endDate . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($bookings) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'Booking Code',
                'Customer Name',
                'Customer Email',
                'Event',
                'Tickets',
                'Total Amount',
                'Status',
                'Date'
            ]);

            // Data rows
            foreach ($bookings as $booking) {
                $event = $booking->details->first()->ticket->event ?? null;
                fputcsv($file, [
                    $booking->booking_code,
                    $booking->user->name,
                    $booking->user->email,
                    $event ? $event->name : '-',
                    $booking->total_tickets,
                    $booking->total_amount,
                    $booking->status,
                    $booking->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}