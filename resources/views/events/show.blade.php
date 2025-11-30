<x-app-layout>
    <x-slot name="title">{{ $event->name }}</x-slot>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Breadcrumb & Title -->
        <div class="mb-6">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                {{ $event->name }}
            </h1>
            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                <span class="px-3 py-1 bg-gray-100 rounded-full">
                    {{ $event->category->name }}
                </span>
                <span>{{ $event->formatted_date }}</span>
                <span>{{ $event->location }}, {{ $event->venue }}</span>
            </div>
        </div>

        <div class="flex justify-end mb-4">
            @auth
                <button 
                    type="button"
                    onclick="toggleFavorite({{ $event->id }}, this)"
                    class="flex items-center gap-2 text-gray-600 hover:text-red-500 transition-colors"
                >
                    <svg class="w-5 h-5 transition-colors {{ $isFavorited ? 'text-red-500' : 'text-gray-400' }}" fill="{{ $isFavorited ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span class="favorite-text">{{ $isFavorited ? 'Saved to favorites' : 'Add to favorites' }}</span>
                </button>
            @else
                <a href="{{ route('login') }}" class="flex items-center gap-2 text-gray-600 hover:text-red-500 transition-colors">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span>Add to favorites</span>
                </a>
            @endauth
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Left Column - Event Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Event Image -->
                <div class="rounded-2xl overflow-hidden">
                    <img 
                        src="{{ $event->image ? asset('storage/' . $event->image) : asset('images/placeholder-event.jpg') }}" 
                        alt="{{ $event->name }}"
                        class="w-full h-[300px] md:h-[400px] object-cover"
                    >
                </div>

                <!-- Date & Location -->
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Date & Time</h3>
                            <p class="text-gray-900">{{ $event->formatted_date }}</p>
                            <p class="text-gray-600">{{ date('H:i', strtotime($event->time_start)) }} - {{ date('H:i', strtotime($event->time_end)) }} WIB</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Location</h3>
                            <p class="text-gray-900">{{ $event->venue }}</p>
                            <p class="text-gray-600">{{ $event->location }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tabs: Description & Reviews -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="border-b border-gray-200">
                        <nav class="flex">
                            <button 
                                onclick="showTab('description')"
                                id="tab-description"
                                class="tab-btn px-6 py-4 text-sm font-medium text-primary-600 border-b-2 border-primary-600"
                            >
                                Description
                            </button>
                            <button 
                                onclick="showTab('reviews')"
                                id="tab-reviews"
                                class="tab-btn px-6 py-4 text-sm font-medium text-gray-500 hover:text-gray-700"
                            >
                                Reviews
                            </button>
                        </nav>
                    </div>

                    <!-- Description Tab -->
                    <div id="content-description" class="p-6">
                        <div class="prose max-w-none">
                            {!! nl2br(e($event->description)) !!}
                        </div>
                    </div>

                    <!-- Reviews Tab -->
                    <div id="content-reviews" class="p-6 hidden">
                        @if($event->reviews->count() > 0)
                            <div class="flex items-center gap-2 mb-6">
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= round($event->average_rating) ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="font-semibold">{{ number_format($event->average_rating, 1) }}</span>
                                <span class="text-gray-500">({{ $event->reviews->count() }} reviews)</span>
                            </div>

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
                                            <span class="text-sm text-gray-500">{{ $review->rating }}</span>
                                        </div>
                                        <p class="text-gray-600">{{ $review->comment }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No reviews yet.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Tickets -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Select Tickets</h2>

                    <form action="{{ auth()->check() && auth()->user()->role === 'user' ? route('user.bookings.store') : route('login') }}" method="{{ auth()->check() && auth()->user()->role === 'user' ? 'POST' : 'GET' }}" id="booking-form">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        <div class="space-y-4 mb-6">
                            @foreach($event->tickets as $ticket)
                                <div class="ticket-card {{ $ticket->isSoldOut() ? 'sold-out' : '' }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h3 class="font-semibold text-gray-900">{{ $ticket->name }}</h3>
                                            <p class="text-xs text-gray-500">
                                                <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $event->date_start->format('d M\'y') }}, {{ date('H:i', strtotime($event->time_start)) }}-{{ date('H:i', strtotime($event->time_end)) }} WIB
                                            </p>
                                        </div>
                                    </div>

                                    @if($ticket->benefits)
                                        <div class="mb-3">
                                            <p class="text-xs text-gray-500 mb-1">Benefit :</p>
                                            <ul class="text-xs text-gray-600 list-disc list-inside">
                                                @foreach($ticket->benefits_array as $benefit)
                                                    <li>{{ $benefit }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="flex items-center justify-between mt-3">
                                        <div>
                                            <span class="font-bold text-gray-900">{{ $ticket->formatted_price }}</span>
                                        </div>
                                        
                                        @if($ticket->isSoldOut())
                                            <span class="text-red-500 text-sm font-medium">Sold Out</span>
                                        @else
                                            <div class="quantity-selector flex items-center gap-2">
                                                <button type="button" class="minus-btn w-7 h-7 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center hover:bg-primary-200 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                    </svg>
                                                </button>
                                                <input 
                                                    type="number" 
                                                    name="tickets[{{ $ticket->id }}]"
                                                    value="0"
                                                    min="0"
                                                    max="{{ $ticket->available_quantity }}"
                                                    data-price="{{ $ticket->price }}"
                                                    class="ticket-quantity w-14 text-center border-0 text-sm font-medium text-gray-900"
                                                    onchange="updateTotal()"
                                                    readonly
                                                >
                                                <button type="button" class="plus-btn w-7 h-7 rounded-full bg-primary-600 text-white flex items-center justify-center hover:bg-primary-700 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Available Quota -->
                                    <div class="flex justify-end mt-2">
                                        @if($ticket->isSoldOut())
                                            <span class="text-xs text-red-500">0 tickets left</span>
                                        @else
                                            <span class="text-xs text-gray-400">{{ $ticket->available_quantity }} tickets left</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Total -->
                        <div class="border-t border-gray-200 pt-4 mb-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Total (<span id="ticket-count">0</span> Ticket)</span>
                                <span id="total-amount" class="text-xl font-bold text-gray-900">Rp 0</span>
                            </div>
                        </div>

                        <!-- Buy Button -->
                        <button 
                            type="submit" 
                            class="w-full btn-primary"
                            id="buy-button"
                            disabled
                        >
                            Buy Ticket
                        </button>

                        @guest
                            <p class="text-xs text-gray-500 text-center mt-3">
                                You need to <a href="{{ route('login') }}" class="text-primary-600 hover:underline">login</a> to buy tickets
                            </p>
                        @endguest
                    </form>
                </div>
            </div>
        </div>

        <!-- Related Events -->
        @if($relatedEvents->count() > 0)
            <section class="mt-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Events</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedEvents as $relatedEvent)
                        @include('components.event-card', ['event' => $relatedEvent])
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    @push('scripts')
    <script>
        // Tab functionality
        function showTab(tabName) {
            // Hide all content
            document.getElementById('content-description').classList.add('hidden');
            document.getElementById('content-reviews').classList.add('hidden');
            
            // Remove active state from all tabs
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('text-primary-600', 'border-b-2', 'border-primary-600');
                btn.classList.add('text-gray-500');
            });
            
            // Show selected content
            document.getElementById('content-' + tabName).classList.remove('hidden');
            
            // Add active state to selected tab
            const activeTab = document.getElementById('tab-' + tabName);
            activeTab.classList.remove('text-gray-500');
            activeTab.classList.add('text-primary-600', 'border-b-2', 'border-primary-600');
        }

        // Quantity selector
        document.querySelectorAll('.quantity-selector').forEach(selector => {
            const minusBtn = selector.querySelector('.minus-btn');
            const plusBtn = selector.querySelector('.plus-btn');
            const input = selector.querySelector('input');

            minusBtn.addEventListener('click', () => {
                const currentValue = parseInt(input.value) || 0;
                if (currentValue > 0) {
                    input.value = currentValue - 1;
                    updateTotal();
                }
            });

            plusBtn.addEventListener('click', () => {
                const currentValue = parseInt(input.value) || 0;
                const maxValue = parseInt(input.max) || 99;
                if (currentValue < maxValue) {
                    input.value = currentValue + 1;
                    updateTotal();
                }
            });
        });

        // Update total
        function updateTotal() {
            let total = 0;
            let ticketCount = 0;
            
            document.querySelectorAll('.ticket-quantity').forEach(input => {
                const quantity = parseInt(input.value) || 0;
                const price = parseFloat(input.dataset.price) || 0;
                total += quantity * price;
                ticketCount += quantity;
            });

            document.getElementById('total-amount').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
            document.getElementById('ticket-count').textContent = ticketCount;
            
            // Enable/disable buy button
            const buyButton = document.getElementById('buy-button');
            buyButton.disabled = ticketCount === 0;
        }

       
    </script>
    @endpush
</x-app-layout>