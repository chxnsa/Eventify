<x-organizer-layout>
    <x-slot name="title">Booking Details</x-slot>

    @php
        $event = $booking->details->first()->ticket->event;
    @endphp

    <!-- Back Button -->
    <a href="{{ route('organizer.bookings.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Bookings
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Booking Info Card -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
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

                <div class="p-6">
                    <!-- Event Info -->
                    <div class="flex items-start gap-4 mb-6 pb-6 border-b border-gray-100">
                        <img 
                            src="{{ $event->image ? asset('storage/' . $event->image) : 'https://placehold.co/100x100/006ACD/white?text=E' }}" 
                            alt="{{ $event->name }}"
                            class="w-20 h-20 object-cover rounded-lg"
                        >
                        <div>
                            <h2 class="font-bold text-gray-900 text-lg">{{ $event->name }}</h2>
                            <p class="text-gray-600">{{ $event->date_start->format('l, d F Y') }}</p>
                            <p class="text-gray-600">{{ $event->venue }}, {{ $event->location }}</p>
                        </div>
                    </div>

                    <!-- Ticket Details -->
                    <h3 class="font-semibold text-gray-900 mb-4">Ticket Details</h3>
                    <div class="space-y-3 mb-6">
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

                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <p class="font-semibold text-gray-900">Total ({{ $booking->total_tickets }} Tickets)</p>
                        <p class="text-xl font-bold text-gray-900">{{ $booking->formatted_total }}</p>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Timeline</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Booking Created</p>
                            <p class="text-sm text-gray-500">{{ $booking->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    @if($booking->approved_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Booking Approved</p>
                                <p class="text-sm text-gray-500">{{ $booking->approved_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($booking->cancelled_at)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Booking Cancelled</p>
                                <p class="text-sm text-gray-500">{{ $booking->cancelled_at->format('d M Y, H:i') }}</p>
                                @if($booking->cancellation_reason)
                                    <p class="text-sm text-gray-600 mt-1">Reason: {{ $booking->cancellation_reason }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Customer</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Name</p>
                        <p class="font-medium text-gray-900">{{ $booking->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-gray-900">{{ $booking->user->email }}</p>
                    </div>
                    @if($booking->user->phone)
                        <div>
                            <p class="text-sm text-gray-500">Phone</p>
                            <p class="font-medium text-gray-900">{{ $booking->user->phone }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Actions</h3>
                <div class="space-y-3">
                    @if($booking->status === 'pending')
                        <form action="{{ route('organizer.bookings.approve', $booking) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full btn-primary">
                                Approve Booking
                            </button>
                        </form>
                    @endif

                    @if($booking->status !== 'cancelled')
                        <button type="button" onclick="showCancelModal()" class="w-full btn-danger">
                            Cancel Booking
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div id="cancel-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Cancel Booking</h3>
            <form action="{{ route('organizer.bookings.cancel', $booking) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="mb-4">
                    <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for cancellation *</label>
                    <textarea 
                        name="cancellation_reason" 
                        id="cancellation_reason" 
                        rows="3" 
                        class="form-input"
                        placeholder="Please provide a reason..."
                        required
                    ></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="hideCancelModal()" class="btn-secondary flex-1">
                        Back
                    </button>
                    <button type="submit" class="btn-danger flex-1">
                        Confirm Cancel
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
</x-organizer-layout>