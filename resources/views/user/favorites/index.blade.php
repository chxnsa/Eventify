<x-app-layout>
    <x-slot name="title">My Favorites</x-slot>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-8">My Favorites</h1>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($favorites->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($favorites as $favorite)
                    <div class="relative">
                        @include('components.event-card', ['event' => $favorite->event])
                        
                        <!-- Remove Button -->
                        <form action="{{ route('user.favorites.destroy', $favorite) }}" method="POST" class="absolute top-2 right-2 z-10">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-white rounded-full p-2 shadow-md hover:bg-red-50 transition-colors" title="Remove from favorites">
                                <svg class="w-5 h-5 text-red-500 fill-current" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $favorites->links() }}
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-xl shadow-sm">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No favorites yet</h3>
                <p class="text-gray-500 mb-6">Start exploring events and save your favorites!</p>
                <a href="{{ route('events.index') }}" class="btn-primary">
                    Browse Events
                </a>
            </div>
        @endif
    </div>
</x-app-layout>