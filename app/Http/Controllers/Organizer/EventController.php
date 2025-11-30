<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->events()->with(['category', 'tickets']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $events = $query->latest()->paginate(10);

        return view('organizer.events.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('organizer.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string'],
            'date_start' => ['required', 'date', 'after:today'],
            'date_end' => ['nullable', 'date', 'after_or_equal:date_start'],
            'time_start' => ['required'],
            'time_end' => ['required', 'after:time_start'],
            'location' => ['required', 'string', 'max:255'],
            'venue' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'status' => ['required', 'in:draft,published'],
        ]);

        $data = $request->except('image');
        $data['user_id'] = auth()->id();
        $data['slug'] = Str::slug($request->name) . '-' . Str::random(6);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event = Event::create($data);

        return redirect()->route('organizer.events.show', $event)
            ->with('success', 'Event created successfully. Now add tickets for your event.');
    }

    public function show(Event $event)
    {
        // Ensure organizer owns this event
        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

        $event->load(['category', 'tickets', 'reviews.user']);

        // Statistics
        $totalTicketsSold = $event->tickets->sum('sold');
        $totalRevenue = $event->tickets->sum(function ($ticket) {
            return $ticket->sold * $ticket->price;
        });

        return view('organizer.events.show', compact('event', 'totalTicketsSold', 'totalRevenue'));
    }

    public function edit(Event $event)
    {
        // Ensure organizer owns this event
        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

        $categories = Category::all();

        return view('organizer.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        // Ensure organizer owns this event
        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string'],
            'date_start' => ['required', 'date'],
            'date_end' => ['nullable', 'date', 'after_or_equal:date_start'],
            'time_start' => ['required'],
            'time_end' => ['required', 'after:time_start'],
            'location' => ['required', 'string', 'max:255'],
            'venue' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'status' => ['required', 'in:draft,published,cancelled'],
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($data);

        return redirect()->route('organizer.events.show', $event)
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        // Ensure organizer owns this event
        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

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

        return redirect()->route('organizer.events.index')
            ->with('success', 'Event deleted successfully.');
    }
}