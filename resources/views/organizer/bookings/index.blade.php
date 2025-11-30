<x-organizer-layout>
    <x-slot name="title">Bookings</x-slot>

    <h1 class="text-2xl font-bold text-gray-900 mb-8">Bookings</h1>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form action="{{ route('organizer.bookings.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Search booking code..."
                    class="form-input"
                >
            </div>
            <select name="event_id" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Events</option>
                @foreach($events as $event)
                    <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                        {{ Str::limit($event->name, 30) }}
                    </option>
                @endforeach
            </select>
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="btn-secondary">Filter</button>
        </form>
    </div>

    <!-- Bookings Table -->
    @if($bookings->count() > 0)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Booking Code</th>
                            <th>Customer</th>
                            <th>Event</th>
                            <th>Tickets</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            @php
                                $event = $booking->details->first()->ticket->event;
                            @endphp
                            <tr>
                                <td class="font-mono font-semibold">{{ $booking->booking_code }}</td>
                                <td>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $booking->user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $booking->user->email }}</p>
                                    </div>
                                </td>
                                <td>{{ Str::limit($event->name, 25) }}</td>
                                <td>{{ $booking->total_tickets }}</td>
                                <td class="font-semibold">{{ $booking->formatted_total }}</td>
                                <td>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $booking->status_color }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td>{{ $booking->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('organizer.bookings.show', $booking) }}" class="text-primary-600 hover:text-primary-700" title="View">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        @if($booking->status === 'pending')
                                            <form action="{{ route('organizer.bookings.approve', $booking) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-600 hover:text-green-700" title="Approve">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $bookings->withQueryString()->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-xl shadow-sm">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">No bookings yet</h3>
            <p class="text-gray-500">Bookings will appear here once customers start purchasing tickets.</p>
        </div>
    @endif
</x-organizer-layout>