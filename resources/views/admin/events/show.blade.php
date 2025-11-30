<x-admin-layout>
    <x-slot name="title">{{ $event->name }}</x-slot>

    <!-- Back Button -->
    <a href="{{ route('admin.events.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Events
    </a>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Event Header -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="md:flex">
            <div class="md:w-1/3">
                <img 
                    src="{{ $event->image ? asset('storage/' . $event->image) : 'https://placehold.co/400x300/006ACD/white?text=Event' }}" 
                    alt="{{ $event->name }}"
                    class="w-full h-64 md:h-full object-cover"
                >
            </div>
            <div class="md:w-2/3 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        @php
                            $statusColors = [
                                'draft' => 'bg-gray-100 text-gray-800',
                                'published' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$event->status] }} mb-2">
                            {{ ucfirst($event->status) }}
                        </span>
                        @if($event->is_featured)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mb-2 ml-2">
                                Featured
                            </span>
                        @endif
                        <h1 class="text-2xl font-bold text-gray-900">{{ $event->name }}</h1>
                        <p class="text-gray-500">{{ $event->category->name }}</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.events.edit', $event) }}" class="btn-secondary text-sm">
                            Edit
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                    <div>
                        <p class="text-gray-500">Date</p>
                        <p class="font-semibold text-gray-900">{{ $event->formatted_date }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Time</p>
                        <p class="font-semibold text-gray-900">{{ $event->formatted_time }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Location</p>
                        <p class="font-semibold text-gray-900">{{ $event->location }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Venue</p>
                        <p class="font-semibold text-gray-900">{{ $event->venue }}</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <p class="text-gray-500 text-sm">Organizer</p>
                    <a href="{{ route('admin.users.show', $event->organizer) }}" class="text-primary-600 hover:text-primary-700 font-semibold">
                        {{ $event->organizer->name }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="stats-card">
            <p class="text-sm text-gray-500">Total Tickets Sold</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalTicketsSold) }}</p>
        </div>
        <div class="stats-card">
            <p class="text-sm text-gray-500">Total Revenue</p>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="stats-card">
            <p class="text-sm text-gray-500">Average Rating</p>
            <div class="flex items-center gap-2">
                <p class="text-2xl font-bold text-gray-900">{{ number_format($event->average_rating, 1) }}</p>
                <span class="text-yellow-400">
                    <svg class="w-6 h-6 fill-current" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </span>
            </div>
        </div>
    </div>

    <!-- Tickets -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h3 class="font-semibold text-gray-900 mb-4">Tickets ({{ $event->tickets->count() }})</h3>
        @if($event->tickets->count() > 0)
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Ticket Name</th>
                            <th>Price</th>
                            <th>Sold</th>
                            <th>Quota</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($event->tickets as $ticket)
                            <tr>
                                <td class="font-medium">{{ $ticket->name }}</td>
                                <td>{{ $ticket->formatted_price }}</td>
                                <td>{{ $ticket->sold }}</td>
                                <td>{{ $ticket->quota }}</td>
                                <td class="font-semibold">Rp {{ number_format($ticket->sold * $ticket->price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-4">No tickets created.</p>
        @endif
    </div>

    <!-- Description -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-gray-900 mb-4">Description</h3>
        <div class="prose max-w-none">
            {!! nl2br(e($event->description)) !!}
        </div>
    </div>
</x-admin-layout>