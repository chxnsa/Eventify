<x-admin-layout>
    <x-slot name="title">Events Management</x-slot>

    <h1 class="text-2xl font-bold text-gray-900 mb-8">Events Management</h1>

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
        <form action="{{ route('admin.events.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Search events..."
                    class="form-input"
                >
            </div>
            <select name="category" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
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
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Organizer</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Tickets</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <img 
                                        src="{{ $event->image ? asset('storage/' . $event->image) : 'https://placehold.co/50x50/006ACD/white?text=E' }}" 
                                        alt="{{ $event->name }}"
                                        class="w-10 h-10 rounded-lg object-cover"
                                    >
                                    <span class="font-medium text-gray-900">{{ Str::limit($event->name, 30) }}</span>
                                </div>
                            </td>
                            <td>{{ $event->organizer->name }}</td>
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
                                <form action="{{ route('admin.events.toggle-featured', $event) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="{{ $event->is_featured ? 'text-yellow-500' : 'text-gray-300' }} hover:text-yellow-500">
                                        <svg class="w-5 h-5 {{ $event->is_featured ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.events.show', $event) }}" class="text-primary-600 hover:text-primary-700" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.events.edit', $event) }}" class="text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500">No events found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $events->withQueryString()->links() }}
    </div>
</x-admin-layout>