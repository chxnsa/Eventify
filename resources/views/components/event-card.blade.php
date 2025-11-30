@props(['event'])

<div class="bg-white rounded-xl shadow-sm overflow-hidden group relative">
    <!-- Favorite Button -->
    @auth
        <button 
            type="button"
            onclick="toggleFavorite({{ $event->id }}, this)"
            class="absolute top-3 right-3 z-10 w-9 h-9 bg-white/90 backdrop-blur rounded-full flex items-center justify-center shadow-sm hover:bg-white transition-colors"
        >
            @php
                $isFavorited = auth()->user()->favorites()->where('event_id', $event->id)->exists();
            @endphp
            <svg class="w-5 h-5 transition-colors {{ $isFavorited ? 'text-red-500 fill-red-500' : 'text-gray-400' }}" fill="{{ $isFavorited ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </button>
    @endauth

    <!-- Image -->
    <a href="{{ route('events.show', $event->slug) }}" class="block">
        <div class="aspect-[4/3] overflow-hidden">
            <img 
                src="{{ $event->image ? asset('storage/' . $event->image) : 'https://placehold.co/400x300/006ACD/white?text=' . urlencode($event->name) }}" 
                alt="{{ $event->name }}"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            >
        </div>
    </a>

    <!-- Content -->
    <div class="p-4">
        <a href="{{ route('events.show', $event->slug) }}">
            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-primary-600 transition-colors">
                {{ $event->name }}
            </h3>
        </a>

        <div class="space-y-1 text-sm text-gray-500 mb-3">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ $event->date_start->format('d M Y') }}</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>{{ $event->location }}</span>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <p class="text-primary-600 font-bold">
                @if($event->tickets->count() > 0)
                    Rp {{ number_format($event->tickets->min('price'), 0, ',', '.') }}
                @else
                    Free
                @endif
            </p>
            @if($event->tickets->sum('quota') - $event->tickets->sum('sold') <= 10 && $event->tickets->sum('quota') > 0)
                <span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded-full">
                    {{ $event->tickets->sum('quota') - $event->tickets->sum('sold') }} left
                </span>
            @endif
        </div>
    </div>
</div>