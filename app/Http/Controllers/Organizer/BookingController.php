<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Booking::with(['user', 'details.ticket.event'])
            ->whereHas('details.ticket.event', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by event
        if ($request->filled('event_id')) {
            $query->whereHas('details.ticket', function ($q) use ($request) {
                $q->where('event_id', $request->event_id);
            });
        }

        // Search by booking code
        if ($request->filled('search')) {
            $query->where('booking_code', 'like', '%' . $request->search . '%');
        }

        $bookings = $query->latest()->paginate(15);

        // Get organizer's events for filter
        $events = $user->events()->select('id', 'name')->get();

        return view('organizer.bookings.index', compact('bookings', 'events'));
    }

    public function show(Booking $booking)
    {
        // Ensure organizer owns the event
        $event = $booking->details->first()->ticket->event;
        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['user', 'details.ticket.event']);

        return view('organizer.bookings.show', compact('booking'));
    }

    public function approve(Booking $booking)
    {
        // Ensure organizer owns the event
        $event = $booking->details->first()->ticket->event;
        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

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
        // Ensure organizer owns the event
        $event = $booking->details->first()->ticket->event;
        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

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