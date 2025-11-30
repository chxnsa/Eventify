<nav class="sticky top-0 z-50 px-4 py-4">
    <div class="max-w-7xl mx-auto">
        <div class="bg-primary-600 rounded-full px-6 py-3 flex items-center justify-between shadow-lg">
            <!-- Logo -->
            <a href="{{ route('organizer.dashboard') }}" class="flex-shrink-0">
                <img src="{{ asset('images/logo-white.svg') }}" alt="Eventify" class="h-7">
            </a>


            <!-- Navigation Links -->
            <div class="flex items-center space-x-6">
                <a href="{{ route('organizer.dashboard') }}" class="text-white font-medium hover:text-white/80 transition-colors {{ request()->routeIs('organizer.dashboard') ? 'text-white' : 'text-white/90' }}">
                    Home
                </a>
                <a href="{{ route('organizer.events.index') }}" class="text-white font-medium hover:text-white/80 transition-colors {{ request()->routeIs('organizer.events.*') ? 'text-white' : 'text-white/90' }}">
                    Events
                </a>
                <a href="{{ route('organizer.bookings.index') }}" class="text-white font-medium hover:text-white/80 transition-colors {{ request()->routeIs('organizer.bookings.*') ? 'text-white' : 'text-white/90' }}">
                    Bookings
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-white font-medium hover:text-white/80 transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>