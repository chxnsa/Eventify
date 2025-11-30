<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function create(Event $event)
    {
        // Ensure organizer owns this event
        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

        return view('organizer.tickets.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        // Ensure organizer owns this event
        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'quota' => ['required', 'integer', 'min:1'],
            'sale_start' => ['required', 'date'],
            'sale_end' => ['required', 'date', 'after:sale_start'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $data = $request->except('image');
        $data['event_id'] = $event->id;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('tickets', 'public');
        }

        Ticket::create($data);

        return redirect()->route('organizer.events.show', $event)
            ->with('success', 'Ticket created successfully.');
    }

    public function edit(Event $event, Ticket $ticket)
    {
        // Ensure organizer owns this event
        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

        return view('organizer.tickets.edit', compact('event', 'ticket'));
    }

    public function update(Request $request, Event $event, Ticket $ticket)
    {
        // Ensure organizer owns this event
        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'quota' => ['required', 'integer', 'min:' . $ticket->sold],
            'sale_start' => ['required', 'date'],
            'sale_end' => ['required', 'date', 'after:sale_start'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($ticket->image) {
                Storage::disk('public')->delete($ticket->image);
            }
            $data['image'] = $request->file('image')->store('tickets', 'public');
        }

        $ticket->update($data);

        return redirect()->route('organizer.events.show', $event)
            ->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Event $event, Ticket $ticket)
    {
        // Ensure organizer owns this event
        if ($event->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if ticket has been sold
        if ($ticket->sold > 0) {
            return back()->with('error', 'Cannot delete ticket that has been sold.');
        }

        if ($ticket->image) {
            Storage::disk('public')->delete($ticket->image);
        }

        $ticket->delete();

        return redirect()->route('organizer.events.show', $event)
            ->with('success', 'Ticket deleted successfully.');
    }
}