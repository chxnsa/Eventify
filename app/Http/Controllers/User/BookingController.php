<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Event;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = $user->bookings()->with(['details.ticket.event']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(10);

        return view('user.bookings.index', compact('bookings'));
    }

    public function create(Request $request)
    {
        $event = Event::with('tickets')->findOrFail($request->event_id);
        $tickets = $request->tickets ?? [];

        return view('user.bookings.create', compact('event', 'tickets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'tickets' => 'required|array',
            'tickets.*' => 'integer|min:0',
        ]);

        $event = Event::with('tickets')->findOrFail($request->event_id);
        $ticketSelections = array_filter($request->tickets, fn($qty) => $qty > 0);

        if (empty($ticketSelections)) {
            return back()->with('error', 'Please select at least one ticket.');
        }

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $bookingDetails = [];

            foreach ($ticketSelections as $ticketId => $quantity) {
                $ticket = Ticket::findOrFail($ticketId);

                // Check availability
                if ($ticket->available_quantity < $quantity) {
                    throw new \Exception("Not enough tickets available for {$ticket->name}");
                }

                $subtotal = $ticket->price * $quantity;
                $totalAmount += $subtotal;

                $bookingDetails[] = [
                    'ticket_id' => $ticket->id,
                    'quantity' => $quantity,
                    'price' => $ticket->price,
                    'subtotal' => $subtotal,
                ];

                // Update sold count
                $ticket->increment('sold', $quantity);
            }

            // Create booking
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'cancellation_deadline' => $event->date_start->subDays(1),
            ]);

            // Create booking details
            foreach ($bookingDetails as $detail) {
                $booking->details()->create($detail);
            }

            DB::commit();

            return redirect()->route('user.bookings.show', $booking)
                ->with('success', 'Booking created successfully! Please wait for approval.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['details.ticket.event', 'review']);

        return view('user.bookings.show', compact('booking'));
    }

    public function ticket(Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== 'approved') {
            return back()->with('error', 'Ticket is not available yet.');
        }

        $booking->load(['details.ticket.event']);

        return view('user.bookings.ticket', compact('booking'));
    }

    /**
     * Cancel booking
     */
    public function cancel(Request $request, Booking $booking)
    {
        // Pastikan booking milik user yang login
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Cek apakah bisa di-cancel
        if (!$booking->canBeCancelled()) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }

        $request->validate([
            'cancellation_reason' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            DB::beginTransaction();

            // Kembalikan stock tiket (untuk semua status booking)
            foreach ($booking->details as $detail) {
                $detail->ticket->decrement('sold', $detail->quantity);
            }

            // Update booking status
            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $request->cancellation_reason ?? 'Cancelled by user',
            ]);

            DB::commit();

            return redirect()->route('user.bookings.index')
                ->with('success', 'Booking cancelled successfully. Ticket quota has been restored.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel booking. Please try again.');
        }
    }

    /**
     * Download ticket as PDF
     */
    public function downloadTicket(Booking $booking)
    {
        // Pastikan booking milik user yang login
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Hanya booking approved yang bisa download
        if ($booking->status !== 'approved') {
            return back()->with('error', 'Ticket is not available yet.');
        }

        $booking->load(['user', 'details.ticket.event']);
        $event = $booking->details->first()->ticket->event;

        $pdf = Pdf::loadView('user.bookings.ticket-pdf', compact('booking', 'event'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('ticket-' . $booking->booking_code . '.pdf');
    }
}