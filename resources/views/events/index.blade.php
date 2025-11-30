<x-app-layout>
    <x-slot name="title">{{ isset($category) ? $category->name : 'All Events' }}</x-slot>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Category Filters -->
        <div class="mb-8">
            <div class="flex flex-wrap gap-3 overflow-x-auto pb-2 scrollbar-hide">
                <a 
                    href="{{ route('events.index') }}" 
                    class="category-pill {{ !request('category') ? 'active' : '' }}"
                >
                    All
                </a>
                @foreach($categories as $cat)
                    <a 
                        href="{{ route('events.index', array_merge(request()->except('category', 'page'), ['category' => $cat->id])) }}" 
                        class="category-pill {{ request('category') == $cat->id ? 'active' : '' }}"
                    >
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Results Info & Filters -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
            <p class="text-gray-600">
                Showing <span class="font-semibold">{{ $events->total() }}</span> events
            </p>

            <!-- Filters -->
            <form action="{{ route('events.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <!-- Location Dropdown -->
                <select name="location" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-primary-500 focus:border-primary-500" onchange="this.form.submit()">
                    <option value="">All Locations</option>
                    @foreach(config('locations.cities') as $city)
                        <option value="{{ $city }}" {{ request('location') == $city ? 'selected' : '' }}>
                            {{ $city }}
                        </option>
                    @endforeach
                </select>

                <!-- Date Picker -->
                <input 
                    type="date" 
                    name="date" 
                    value="{{ request('date') }}"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-primary-500 focus:border-primary-500"
                    min="{{ date('Y-m-d') }}"
                    onchange="this.form.submit()"
                >

                <!-- Sort -->
                <select name="sort" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-primary-500 focus:border-primary-500" onchange="this.form.submit()">
                    <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Date</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High</option>
                </select>

                <!-- Clear Button -->
                @if(request()->hasAny(['location', 'date']))
                    <a href="{{ route('events.index', request()->only(['category', 'search', 'sort'])) }}" class="text-sm text-gray-500 hover:text-gray-700">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Active Filters -->
        @if(request()->hasAny(['search', 'location', 'date']))
            <div class="flex flex-wrap gap-2 mb-6">
                @if(request('search'))
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-sm">
                        "{{ request('search') }}"
                        <a href="{{ route('events.index', request()->except('search')) }}" class="hover:text-primary-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    </span>
                @endif
                @if(request('location'))
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-sm">
                        {{ request('location') }}
                        <a href="{{ route('events.index', request()->except('location')) }}" class="hover:text-primary-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    </span>
                @endif
                @if(request('date'))
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-sm">
                        {{ \Carbon\Carbon::parse(request('date'))->format('d M Y') }}
                        <a href="{{ route('events.index', request()->except('date')) }}" class="hover:text-primary-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    </span>
                @endif
            </div>
        @endif

        <!-- Events Grid -->
        @if($events->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($events as $event)
                    @include('components.event-card', ['event' => $event])
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $events->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No events found</h3>
                <p class="text-gray-500 mb-6">Try adjusting your filters or search terms.</p>
                <a href="{{ route('events.index') }}" class="btn-primary">
                    View All Events
                </a>
            </div>
        @endif
    </div>
</x-app-layout>