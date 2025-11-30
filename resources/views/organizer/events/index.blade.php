<x-organizer-layout>
    <x-slot name="title">My Events</x-slot>

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-gray-900">My Events</h1>
        <a href="{{ route('organizer.events.create') }}" class="btn-primary">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create Event
        </a>
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

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form action="{{ route('organizer.events.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Search events..."
                    class="form-input"
                >
            </div>
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Status</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="btn-secondary">Filter</button>
        </form>
    </div>

    <!-- Events Table -->
    @if($events->count() > 0)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Tickets</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <img 
                                            src="{{ $event->image ? asset('storage/' . $event->image) : 'https://placehold.co/60x60/006ACD/white?text=E' }}" 
                                            alt="{{ $event->name }}"
                                            class="w-12 h-12 rounded-lg object-cover"
                                        >
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ Str::limit($event->name, 30) }}</p>
                                            <p class="text-sm text-gray-500">{{ $event->venue }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $event->category->name }}</td>
                                <td>{{ $event->date_start->format('d M Y') }}</td>
                                <td>
                                    <span class="font-semibold">{{ $event->tickets->sum('sold') }}</span>
                                    <span class="text-gray-500">/ {{ $event->tickets->sum('quota') }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'draft' => 'bg-gray-100 text-gray-800',
                                            'published' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$event->status] }}">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('organizer.events.show', $event) }}" class="text-primary-600 hover:text-primary-700" title="View">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('organizer.events.edit', $event) }}" class="text-yellow-600 hover:text-yellow-700" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('organizer.events.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this event?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-700" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $events->withQueryString()->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-xl shadow-sm">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">No events yet</h3>
            <p class="text-gray-500 mb-6">Create your first event and start selling tickets!</p>
            <a href="{{ route('organizer.events.create') }}" class="btn-primary">
                Create Event
            </a>
        </div>
    @endif
</x-organizer-layout>