<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['category', 'organizer', 'tickets']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        $events = $query->latest()->paginate(15);
        $categories = Category::all();

        return view('admin.events.index', compact('events', 'categories'));
    }

    public function show(Event $event)
    {
        $event->load(['category', 'organizer', 'tickets', 'reviews.user']);

        // Statistics
        $totalTicketsSold = $event->tickets->sum('sold');
        $totalRevenue = $event->tickets->sum(function ($ticket) {
            return $ticket->sold * $ticket->price;
        });

        return view('admin.events.show', compact('event', 'totalTicketsSold', 'totalRevenue'));
    }

    public function edit(Event $event)
    {
        $categories = Category::all();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'status' => ['required', 'in:draft,published,cancelled'],
            'is_featured' => ['boolean'],
        ]);

        $event->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'status' => $request->status,
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        // Check if event has bookings
        $hasBookings = $event->tickets()->whereHas('bookingDetails')->exists();

        if ($hasBookings) {
            return back()->with('error', 'Cannot delete event with existing bookings.');
        }

        // Delete image
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }

    public function toggleFeatured(Event $event)
    {
        $event->update(['is_featured' => !$event->is_featured]);

        return back()->with('success', 'Event featured status updated.');
    }
}