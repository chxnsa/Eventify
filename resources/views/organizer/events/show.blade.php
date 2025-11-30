<x-organizer-layout>
    <x-slot name="title">{{ $event->name }}</x-slot>

    <!-- Back Button -->
    <a href="{{ route('organizer.events.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6">
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

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
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
                        <h1 class="text-2xl font-bold text-gray-900">{{ $event->name }}</h1>
                        <p class="text-gray-500">{{ $event->category->name }}</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('organizer.events.edit', $event) }}" class="btn-secondary text-sm">
                            Edit Event
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
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
                <div class="flex text-yellow-400">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= round($event->average_rating) ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets Section -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-gray-900">Tickets</h2>
            <a href="{{ route('organizer.tickets.create', $event) }}" class="btn-primary text-sm">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Ticket
            </a>
        </div>

        @if($event->tickets->count() > 0)
            <div class="space-y-4">
                @foreach($event->tickets as $ticket)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $ticket->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $ticket->description }}</p>
                                <p class="text-lg font-bold text-gray-900 mt-2">{{ $ticket->formatted_price }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Sold</p>
                                <p class="font-semibold">
                                    <span class="text-primary-600">{{ $ticket->sold }}</span> / {{ $ticket->quota }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Progress bar -->
                        <div class="mt-3">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-primary-600 h-2 rounded-full" style="width: {{ ($ticket->sold / $ticket->quota) * 100 }}%"></div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 mt-4">
                            <a href="{{ route('organizer.tickets.edit', [$event, $ticket]) }}" class="text-sm text-primary-600 hover:text-primary-700">
                                Edit
                            </a>
                            @if($ticket->sold == 0)
                                <form action="{{ route('organizer.tickets.destroy', [$event, $ticket]) }}" method="POST" class="inline" onsubmit="return confirm('Delete this ticket?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-700">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
                <p class="text-gray-500 mb-4">No tickets created yet</p>
                <a href="{{ route('organizer.tickets.create', $event) }}" class="btn-primary">
                    Add Ticket
                </a>
            </div>
        @endif
    </div>

    <!-- Description -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Description</h2>
        <div class="prose max-w-none">
            {!! nl2br(e($event->description)) !!}
        </div>
    </div>

    <!-- Reviews -->
    @if($event->reviews->count() > 0)
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Reviews ({{ $event->reviews->count() }})</h2>
            <div class="space-y-4">
                @foreach($event->reviews as $review)
                    <div class="border-b border-gray-100 pb-4 last:border-0">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="font-semibold text-gray-900">{{ $review->user->name }}</span>
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-sm text-gray-500">{{ $review->created_at->format('d M Y') }}</span>
                        </div>
                        <p class="text-gray-600">{{ $review->comment }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</x-organizer-layout>