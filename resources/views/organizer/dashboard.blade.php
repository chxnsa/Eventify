<x-organizer-layout>
    <x-slot name="title">Dashboard</x-slot>

    <!-- Welcome -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-600">Here's your event performance overview.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="stats-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Events</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalEvents }}</p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stats-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Tickets Sold</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalTicketsSold) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stats-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Monthly Revenue</h3>
            <canvas id="revenueChart" height="200"></canvas>
        </div>

        <!-- Tickets by Event Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Tickets Sold by Event</h3>
            <canvas id="ticketsChart" height="200"></canvas>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-semibold text-gray-900">Recent Bookings</h3>
            <a href="{{ route('organizer.bookings.index') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                View All
            </a>
        </div>

        @if($recentBookings->count() > 0)
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Booking Code</th>
                            <th>Customer</th>
                            <th>Event</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentBookings as $booking)
                            @php
                                $event = $booking->details->first()->ticket->event;
                            @endphp
                            <tr>
                                <td class="font-mono">{{ $booking->booking_code }}</td>
                                <td>{{ $booking->user->name }}</td>
                                <td>{{ Str::limit($event->name, 30) }}</td>
                                <td>{{ $booking->formatted_total }}</td>
                                <td>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $booking->status_color }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td>{{ $booking->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-gray-500 py-8">No bookings yet.</p>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyRevenue->map(fn($r) => date('M Y', mktime(0, 0, 0, $r->month, 1, $r->year)))) !!},
                datasets: [{
                    label: 'Revenue',
                    data: {!! json_encode($monthlyRevenue->pluck('total')) !!},
                    borderColor: '#006ACD',
                    backgroundColor: 'rgba(0, 106, 205, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Tickets Chart
        const ticketsCtx = document.getElementById('ticketsChart').getContext('2d');
        new Chart(ticketsCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($ticketsByEvent->pluck('name')->map(fn($n) => Str::limit($n, 20))) !!},
                datasets: [{
                    label: 'Tickets Sold',
                    data: {!! json_encode($ticketsByEvent->pluck('total_tickets')) !!},
                    backgroundColor: '#006ACD'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    @endpush
</x-organizer-layout>