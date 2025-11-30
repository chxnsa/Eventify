<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Welcome -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-600">Here's what's happening with your events.</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="stats-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Bookings</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalBookings }}</p>
                    </div>
                    <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stats-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Favorites</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalFavorites }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stats-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Upcoming Events</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $upcomingBookings->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Bookings -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-gray-900">Upcoming Events</h2>
                <a href="{{ route('user.bookings.index') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                    View All
                </a>
            </div>

            @if($upcomingBookings->count() > 0)
                <div class="space-y-4">
                    @foreach($upcomingBookings as $booking)
                        @php
                            $event = $booking->details->first()->ticket->event;
                        @endphp
                        <div class="flex items-center gap-4 p-4 border border-gray-100 rounded-lg hover:bg-gray-50 transition-colors">
                            <img 
                                src="{{ $event->image ? asset('storage/' . $event->image) : 'https://placehold.co/100x100/006ACD/white?text=Event' }}" 
                                alt="{{ $event->name }}"
                                class="w-16 h-16 object-cover rounded-lg"
                            >
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $event->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $event->date_start->format('D, d M Y') }} - {{ $event->venue }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ ucfirst($booking->status) }}
                                </span>
                                <p class="text-sm text-gray-500 mt-1">{{ $booking->total_tickets }} Tickets</p>
                            </div>
                            <a href="{{ route('user.bookings.show', $booking) }}" class="text-primary-600 hover:text-primary-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                    <p class="text-gray-500 mb-4">No upcoming events</p>
                    <a href="{{ route('events.index') }}" class="btn-primary">
                        Browse Events
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>