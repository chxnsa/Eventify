<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'details.ticket.event']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by booking code
        if ($request->filled('search')) {
            $query->where('booking_code', 'like', '%' . $request->search . '%');
        }

        $bookings = $query->latest()->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'details.ticket.event', 'review']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function approve(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be approved.');
        }

        $booking->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Booking approved successfully.');
    }

    public function cancel(Request $request, Booking $booking)
    {
        if ($booking->status === 'cancelled') {
            return back()->with('error', 'Booking is already cancelled.');
        }

        $request->validate([
            'cancellation_reason' => ['required', 'string', 'max:500'],
        ]);

        try {
            DB::beginTransaction();

            // Restore ticket stock
            foreach ($booking->details as $detail) {
                $detail->ticket->decrement('sold', $detail->quantity);
            }

            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $request->cancellation_reason,
            ]);

            DB::commit();

            return back()->with('success', 'Booking cancelled successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel booking.');
        }
    }
}