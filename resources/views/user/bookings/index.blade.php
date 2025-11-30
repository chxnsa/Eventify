<x-app-layout>
    <x-slot name="title">My Tickets</x-slot>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-900">My Tickets</h1>

            <!-- Filter -->
            <form action="{{ route('user.bookings.index') }}" method="GET" class="flex items-center gap-2">
                <select 
                    name="status" 
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-primary-500 focus:border-primary-500"
                    onchange="this.form.submit()"
                >
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </form>
        </div>

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

        @if($bookings->count() > 0)
            <div class="space-y-4">
                @foreach($bookings as $booking)
                    @php
                        $event = $booking->details->first()->ticket->event;
                    @endphp
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="flex flex-col md:flex-row">
                            <!-- Event Image -->
                            <div class="md:w-48 h-32 md:h-auto flex-shrink-0">
                                <img 
                                    src="{{ $event->image ? asset('storage/' . $event->image) : 'https://placehold.co/200x150/006ACD/white?text=Event' }}" 
                                    alt="{{ $event->name }}"
                                    class="w-full h-full object-cover"
                                >
                            </div>

                            <!-- Booking Info -->
                            <div class="flex-1 p-4 md:p-6">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                    <div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $booking->status_color }} mb-2">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                        <h3 class="font-bold text-gray-900 text-lg">{{ $event->name }}</h3>
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ $event->date_start->format('l, d F Y') }} - {{ date('H:i', strtotime($event->time_start)) }} WIB
                                        </p>
                                        <p class="text-sm text-gray-500">{{ $event->venue }}, {{ $event->location }}</p>
                                    </div>

                                    <div class="text-left md:text-right">
                                        <p class="text-sm text-gray-500">Booking Code</p>
                                        <p class="font-mono font-semibold text-gray-900">{{ $booking->booking_code }}</p>
                                        <p class="text-sm text-gray-500 mt-2">{{ $booking->total_tickets }} Tickets</p>
                                        <p class="font-bold text-gray-900">{{ $booking->formatted_total }}</p>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center gap-2 mt-4 md:mt-0">
                                    <a href="{{ route('user.bookings.show', $booking) }}" class="btn-secondary text-sm">
                                        View Details
                                    </a>
                                    
                                    @if($booking->status === 'approved')
                                        <a href="{{ route('user.bookings.ticket', $booking) }}" class="btn-secondary text-sm">
                                            View Ticket
                                        </a>
                                        <a href="{{ route('user.bookings.download', $booking) }}" class="btn-primary text-sm">
                                            Download
                                        </a>
                                    @endif

                                    @if($booking->canBeCancelled())
                                        <button 
                                            type="button" 
                                            onclick="showCancelModal('{{ $booking->id }}')"
                                            class="btn-danger text-sm"
                                        >
                                            Cancel
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $bookings->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-xl shadow-sm">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No bookings yet</h3>
                <p class="text-gray-500 mb-6">Start exploring events and book your first ticket!</p>
                <a href="{{ route('events.index') }}" class="btn-primary">
                    Browse Events
                </a>
            </div>
        @endif
    </div>
    <!-- Cancel Modal -->
    <div id="cancel-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Cancel Booking</h3>
            <p class="text-gray-600 mb-4">Are you sure you want to cancel this booking? This action cannot be undone.</p>
            
            <form id="cancel-form" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-2">Reason (optional)</label>
                    <textarea 
                        name="cancellation_reason" 
                        id="cancellation_reason" 
                        rows="3" 
                        class="form-input"
                        placeholder="Why are you cancelling this booking?"
                    ></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="hideCancelModal()" class="btn-secondary flex-1">
                        Back
                    </button>
                    <button type="submit" class="btn-danger flex-1">
                        Yes, Cancel Booking
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function showCancelModal(bookingId) {
            document.getElementById('cancel-form').action = '/user/bookings/' + bookingId + '/cancel';
            document.getElementById('cancel-modal').classList.remove('hidden');
        }

        function hideCancelModal() {
            document.getElementById('cancel-modal').classList.add('hidden');
        }
    </script>
    @endpush
</x-app-layout>