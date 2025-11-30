<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        // Ensure user owns this booking
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Ensure booking is approved
        if ($booking->status !== 'approved') {
            return back()->with('error', 'You can only review approved bookings.');
        }

        // Check if already reviewed
        if ($booking->review) {
            return back()->with('error', 'You have already reviewed this booking.');
        }

        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        // Get event from booking
        $event = $booking->details->first()->ticket->event;

        Review::create([
            'user_id' => auth()->id(),
            'event_id' => $event->id,
            'booking_id' => $booking->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Review submitted successfully.');
    }
}