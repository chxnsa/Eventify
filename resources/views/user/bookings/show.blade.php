<x-app-layout>
    <x-slot name="title">Booking Details</x-slot>

    @php
        $event = $booking->details->first()->ticket->event;
    @endphp

    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Back Button -->
        <a href="{{ route('user.bookings.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to My Tickets
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

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="bg-primary-600 text-white p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-white/70">Booking Code</p>
                        <p class="text-2xl font-mono font-bold">{{ $booking->booking_code }}</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $booking->status === 'approved' ? 'bg-green-500' : ($booking->status === 'pending' ? 'bg-yellow-500' : 'bg-red-500') }} text-white">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
            </div>

            <!-- Event Info -->
            <div class="p-6 border-b border-gray-100">
                <div class="flex gap-4">
                    <img 
                        src="{{ $event->image ? asset('storage/' . $event->image) : 'https://placehold.co/120x120/006ACD/white?text=Event' }}" 
                        alt="{{ $event->name }}"
                        class="w-24 h-24 object-cover rounded-lg"
                    >
                    <div>
                        <h2 class="font-bold text-gray-900 text-xl">{{ $event->name }}</h2>
                        <p class="text-gray-600 mt-1">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $event->date_start->format('l, d F Y') }}
                        </p>
                        <p class="text-gray-600">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ date('H:i', strtotime($event->time_start)) }} - {{ date('H:i', strtotime($event->time_end)) }} WIB
                        </p>
                        <p class="text-gray-600">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $event->venue }}, {{ $event->location }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Ticket Details -->
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900 mb-4">Ticket Details</h3>
                <div class="space-y-3">
                    @foreach($booking->details as $detail)
                        <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                            <div>
                                <p class="font-medium text-gray-900">{{ $detail->ticket->name }}</p>
                                <p class="text-sm text-gray-500">Rp {{ number_format($detail->price, 0, ',', '.') }} x {{ $detail->quantity }}</p>
                            </div>
                            <p class="font-semibold text-gray-900">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200">
                    <p class="font-semibold text-gray-900">Total ({{ $booking->total_tickets }} Tickets)</p>
                    <p class="text-xl font-bold text-gray-900">{{ $booking->formatted_total }}</p>
                </div>
            </div>

            <!-- Booking Info -->
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900 mb-4">Booking Information</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Booked At</p>
                        <p class="text-gray-900">{{ $booking->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if($booking->approved_at)
                        <div>
                            <p class="text-gray-500">Approved At</p>
                            <p class="text-gray-900">{{ $booking->approved_at->format('d M Y, H:i') }}</p>
                        </div>
                    @endif
                    @if($booking->cancelled_at)
                        <div>
                            <p class="text-gray-500">Cancelled At</p>
                            <p class="text-gray-900">{{ $booking->cancelled_at->format('d M Y, H:i') }}</p>
                        </div>
                        @if($booking->cancellation_reason)
                            <div class="col-span-2">
                                <p class="text-gray-500">Cancellation Reason</p>
                                <p class="text-gray-900">{{ $booking->cancellation_reason }}</p>
                            </div>
                        @endif
                    @endif
                    @if($booking->cancellation_deadline && $booking->status !== 'cancelled')
                        <div>
                            <p class="text-gray-500">Cancellation Deadline</p>
                            <p class="text-gray-900">{{ $booking->cancellation_deadline->format('d M Y, H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                @if($booking->status === 'approved')
                    <a href="{{ route('user.bookings.ticket', $booking) }}" class="w-full btn-secondary block text-center">
                        View E-Ticket
                    </a>
                    <a href="{{ route('user.bookings.download', $booking) }}" class="w-full btn-primary block text-center">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download PDF
                    </a>
                @endif

                @if($booking->canBeCancelled())
                    <button 
                        type="button" 
                        onclick="showCancelModal()"
                        class="w-full btn-danger"
                    >
                        Cancel Booking
                    </button>
                    
                    @if($booking->status === 'approved' && $booking->cancellation_deadline)
                        <p class="text-xs text-gray-500 text-center">
                            Cancel before {{ $booking->cancellation_deadline->format('d M Y, H:i') }}
                        </p>
                    @endif
                @endif

                <a href="{{ route('events.show', $booking->details->first()->ticket->event->slug) }}" class="w-full btn-secondary block text-center">
                    View Event
                </a>
            </div>
        </div>

        <!-- Review Section -->
        @if($booking->status === 'approved' && $event->date_start->isPast())
            <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
                <h3 class="font-semibold text-gray-900 mb-4">Leave a Review</h3>

                @if($booking->review)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $booking->review->rating ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-gray-500 text-sm">{{ $booking->review->created_at->format('d M Y') }}</span>
                        </div>
                        <p class="text-gray-700">{{ $booking->review->comment }}</p>
                    </div>
                @else
                    <form action="{{ route('user.reviews.store', $booking) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                            <div class="flex gap-2" id="star-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" class="star-btn" data-rating="{{ $i }}">
                                        <svg class="w-8 h-8 text-gray-300 hover:text-yellow-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="rating-input" value="" required>
                            @error('rating')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Your Review</label>
                            <textarea 
                                name="comment" 
                                id="comment" 
                                rows="4" 
                                class="form-input"
                                placeholder="Share your experience..."
                                required
                            >{{ old('comment') }}</textarea>
                            @error('comment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="btn-primary">
                            Submit Review
                        </button>
                    </form>
                @endif
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        // Star rating
        const starButtons = document.querySelectorAll('.star-btn');
        const ratingInput = document.getElementById('rating-input');

        starButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const rating = this.dataset.rating;
                ratingInput.value = rating;

                starButtons.forEach((b, index) => {
                    const svg = b.querySelector('svg');
                    if (index < rating) {
                        svg.classList.add('fill-current', 'text-yellow-400');
                        svg.classList.remove('text-gray-300');
                    } else {
                        svg.classList.remove('fill-current', 'text-yellow-400');
                        svg.classList.add('text-gray-300');
                    }
                });
            });
        });
    </script>
    @endpush
    <!-- Cancel Modal -->
    @if($booking->canBeCancelled())
    <div id="cancel-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Cancel Booking</h3>
            <p class="text-gray-600 mb-4">Are you sure you want to cancel this booking? This action cannot be undone.</p>
            
            <form action="{{ route('user.bookings.cancel', $booking) }}" method="POST">
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
        function showCancelModal() {
            document.getElementById('cancel-modal').classList.remove('hidden');
        }

        function hideCancelModal() {
            document.getElementById('cancel-modal').classList.add('hidden');
        }
    </script>
    @endpush
    @endif
</x-app-layout>