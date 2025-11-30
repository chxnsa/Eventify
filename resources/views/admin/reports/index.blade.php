<x-admin-layout>
    <x-slot name="title">Reports</x-slot>

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Sales Reports</h1>
        <a href="{{ route('admin.reports.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn-primary">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export CSV
        </a>
    </div>

    <!-- Date Filter -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                <input 
                    type="date" 
                    id="start_date" 
                    name="start_date" 
                    value="{{ $startDate }}"
                    class="form-input"
                >
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input 
                    type="date" 
                    id="end_date" 
                    name="end_date" 
                    value="{{ $endDate }}"
                    class="form-input"
                >
            </div>
            <button type="submit" class="btn-secondary">Apply Filter</button>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stats-card">
            <p class="text-sm text-gray-500">Total Sales</p>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
        </div>
        <div class="stats-card">
            <p class="text-sm text-gray-500">Total Bookings</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalBookings) }}</p>
        </div>
        <div class="stats-card">
            <p class="text-sm text-gray-500">Tickets Sold</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($totalTicketsSold) }}</p>
        </div>
        <div class="stats-card">
            <p class="text-sm text-gray-500">Approval Rate</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalBookings > 0 ? round(($approvedBookings / $totalBookings) * 100) : 0 }}%</p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Daily Sales Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Daily Sales</h3>
            <canvas id="dailySalesChart" height="200"></canvas>
        </div>

        <!-- Booking Status Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-900 mb-4">Booking Status</h3>
            <canvas id="statusChart" height="200"></canvas>
        </div>
    </div>

    <!-- Sales by Event -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h3 class="font-semibold text-gray-900 mb-4">Sales by Event</h3>
        @if($salesByEvent->count() > 0)
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Tickets Sold</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salesByEvent as $sale)
                            <tr>
                                <td class="font-medium">{{ $sale->name }}</td>
                                <td>{{ number_format($sale->tickets_sold) }}</td>
                                <td class="font-semibold">Rp {{ number_format($sale->total_revenue, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-4">No sales data for this period.</p>
        @endif
    </div>

    <!-- Recent Bookings -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-semibold text-gray-900 mb-4">Recent Bookings</h3>
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
                                $event = $booking->details->first()->ticket->event ?? null;
                            @endphp
                            <tr>
                                <td class="font-mono">{{ $booking->booking_code }}</td>
                                <td>{{ $booking->user->name }}</td>
                                <td>{{ $event ? Str::limit($event->name, 25) : '-' }}</td>
                                <td class="font-semibold">{{ $booking->formatted_total }}</td>
                                <td>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $booking->status_color }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td>{{ $booking->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-4">No bookings for this period.</p>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Daily Sales Chart
        const dailyCtx = document.getElementById('dailySalesChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dailySales->pluck('date')) !!},
                datasets: [{
                    label: 'Sales',
                    data: {!! json_encode($dailySales->pluck('total')) !!},
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

        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Approved', 'Pending', 'Cancelled'],
                datasets: [{
                    data: [
                        {{ $bookingStatus['approved'] ?? 0 }},
                        {{ $bookingStatus['pending'] ?? 0 }},
                        {{ $bookingStatus['cancelled'] ?? 0 }}
                    ],
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
    @endpush
</x-admin-layout>