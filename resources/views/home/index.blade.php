<x-app-layout>
    <x-slot name="title">Home</x-slot>

    <!-- Hero Banner -->
    <section class="relative">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="relative rounded-3xl overflow-hidden h-[400px] md:h-[500px]">
                <img 
                    src="{{ asset('images/hero-concert.jpg') }}" 
                    alt="Featured Event" 
                    class="w-full h-full object-cover"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                
              
            </div>
        </div>

        <!-- Decorative Element -->
        <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-24 h-24 -ml-12 opacity-50 pointer-events-none">
            <img src="{{ asset('images/decoration-flower.svg') }}" alt="" class="w-full h-full">
        </div>
    </section>

    <!-- Upcoming Events Section -->
    <section class="py-12 relative">
        <!-- Decorative Element -->
        <div class="absolute left-0 top-0 w-32 h-32 opacity-30 pointer-events-none">
            <img src="{{ asset('images/decoration-flower.svg') }}" alt="" class="w-full h-full">
        </div>

        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-primary-600 mb-8">
                Upcoming Events
            </h2>

            <!-- Events Horizontal Scroll -->
            <div class="relative">
                <div class="flex space-x-6 overflow-x-auto pb-4 scrollbar-hide scroll-container">
                    @forelse($upcomingEvents as $event)
                        <div class="flex-shrink-0 w-[280px]">
                            @include('components.event-card', ['event' => $event])
                        </div>
                    @empty
                        <div class="w-full text-center py-12">
                            <p class="text-gray-500">No upcoming events found.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Scroll Buttons -->
                @if($upcomingEvents->count() > 3)
                    <button class="scroll-left absolute left-0 top-1/2 transform -translate-y-1/2 -ml-4 bg-white shadow-lg rounded-full p-2 hover:bg-gray-100 transition-colors z-10 hidden md:block">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button class="scroll-right absolute right-0 top-1/2 transform -translate-y-1/2 -mr-4 bg-white shadow-lg rounded-full p-2 hover:bg-gray-100 transition-colors z-10 hidden md:block">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                @endif
            </div>

            <!-- View All Button -->
            <div class="text-center mt-8">
                <a href="{{ route('events.index') }}" class="btn-secondary">
                    View All Events
                </a>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">
                Browse by Category
            </h2>

            <div class="flex flex-wrap justify-center gap-3">
                @foreach($categories as $category)
                    <a 
                        href="{{ route('events.category', $category->slug) }}" 
                        class="category-pill"
                    >
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Events Section -->
    @if($featuredEvents->count() > 0)
        <section class="py-12">
            <div class="max-w-7xl mx-auto px-4">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">
                    Featured Events
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($featuredEvents as $event)
                        @include('components.event-card', ['event' => $event])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Partner CTA Section -->
    <section class="py-16 bg-primary-600 relative overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute right-0 top-0 w-48 h-48 opacity-10 pointer-events-none">
            <img src="{{ asset('images/decoration-curl.svg') }}" alt="" class="w-full h-full">
        </div>

        <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Partner with Us
            </h2>
            <p class="text-white/80 text-lg mb-8">
                Create events, track ticket sales, and grow your audience with Eventify.
            </p>
            <a href="{{ route('register.organizer') }}" class="inline-flex items-center bg-white text-primary-600 px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition-all duration-300 hover:shadow-lg">
                Become an Organizer
            </a>
        </div>
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Horizontal scroll functionality
            const container = document.querySelector('.scroll-container');
            const scrollLeftBtn = document.querySelector('.scroll-left');
            const scrollRightBtn = document.querySelector('.scroll-right');

            if (scrollLeftBtn && scrollRightBtn && container) {
                scrollLeftBtn.addEventListener('click', () => {
                    container.scrollBy({ left: -300, behavior: 'smooth' });
                });

                scrollRightBtn.addEventListener('click', () => {
                    container.scrollBy({ left: 300, behavior: 'smooth' });
                });
            }
        });
    </script>
    @endpush
</x-app-layout>