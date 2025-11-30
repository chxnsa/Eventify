<x-app-layout>
    <x-slot name="title">E-Ticket</x-slot>

    @php
        $event = $booking->details->first()->ticket->event;
        $totalTickets = $booking->details->sum('quantity');
    @endphp

    <div class="max-w-2xl mx-auto px-4 py-8">
        <!-- Back Button -->
        <a href="{{ route('user.bookings.show', $booking) }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Booking
        </a>

        <!-- Single Ticket -->
        <div class="ticket-card mb-6">
            <!-- Ticket Container -->
            <div class="bg-primary-600 rounded-2xl p-4 shadow-lg">
                <div class="bg-gray-900 rounded-xl p-6 text-white">
                    <!-- Event Name -->
                    <h1 class="text-xl md:text-2xl font-bold uppercase tracking-wide mb-6">
                        {{ $event->name }}
                    </h1>

                    <!-- Main Content -->
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                        <!-- Left Section -->
                        <div class="flex-1">
                            <!-- Info Boxes -->
                            <div class="flex flex-wrap gap-2 mb-6">
                                <div class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-center">
                                    <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">Code</p>
                                    <p class="text-lg font-bold">{{ substr($booking->booking_code, -6) }}</p>
                                </div>
                                <div class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-center">
                                    <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">Tickets</p>
                                    <p class="text-lg font-bold">{{ $totalTickets }}</p>
                                </div>
                                <div class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-3">
                                    <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">{{ $event->date_start->format('d F Y') }}</p>
                                    <p class="text-lg font-bold">{{ date('H:i', strtotime($event->time_start)) }} WIB</p>
                                </div>
                            </div>

                            <!-- Ticket Details -->
                            <div class="bg-gray-800 border border-gray-700 rounded-lg p-4 mb-6">
                                <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-2">Ticket Details</p>
                                @foreach($booking->details as $detail)
                                    <div class="flex justify-between items-center {{ !$loop->last ? 'mb-2 pb-2 border-b border-gray-700' : '' }}">
                                        <span class="font-medium">{{ $detail->ticket->name }}</span>
                                        <span class="text-gray-400">x{{ $detail->quantity }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Venue -->
                            <div class="border-t border-gray-700 pt-4">
                                <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">Venue</p>
                                <p class="font-semibold">{{ $event->venue }}</p>
                                <p class="text-gray-400 text-sm">{{ $event->location }}</p>
                            </div>
                        </div>

                        <!-- Right Section - QR -->
                        <div class="flex-shrink-0">
                            <div class="bg-white rounded-xl p-4 text-center">
                                <img 
                                    src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode($booking->booking_code) }}" 
                                    alt="QR Code"
                                    class="w-28 h-28 mx-auto mb-2"
                                >
                                <p class="text-[9px] text-gray-600 font-bold">{{ $booking->booking_code }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="border-t-2 border-dashed border-gray-700 my-6"></div>

                    <!-- Footer -->
                    <div class="flex flex-wrap justify-between gap-4">
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">Attendee Name</p>
                            <p class="font-semibold">{{ $booking->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">Email</p>
                            <p class="font-semibold">{{ $booking->user->email }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-1">Total</p>
                            <p class="font-semibold">{{ $booking->formatted_total }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 mt-8">
            <a href="{{ route('user.bookings.download', $booking) }}" class="btn-primary flex-1 text-center">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download PDF
            </a>
            <button onclick="window.print()" class="btn-secondary flex-1">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print Ticket
            </button>
        </div>

        <!-- Note -->
        <p class="text-center text-gray-500 text-sm mt-6">
            Show this ticket at the venue entrance
        </p>
    </div>

    @push('styles')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .ticket-card, .ticket-card * {
                visibility: visible;
            }
            .ticket-card {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            nav, footer, .btn-primary, .btn-secondary, a {
                display: none !important;
            }
        }
    </style>
    @endpush
</x-app-layout>