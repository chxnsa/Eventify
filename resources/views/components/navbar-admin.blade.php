<nav class="sticky top-0 z-50 px-4 py-4">
    <div class="max-w-7xl mx-auto">
        <div class="bg-primary-600 rounded-full px-6 py-3 flex items-center justify-between shadow-lg">
            <!-- Logo -->
            <a href="{{ route('admin.dashboard') }}" class="flex-shrink-0">
                <img src="{{ asset('images/logo-white.svg') }}" alt="Eventify" class="h-7">
            </a>

           

            <!-- Navigation Links -->
            <div class="flex items-center space-x-6">
                <a href="{{ route('admin.dashboard') }}" class="text-white font-medium hover:text-white/80 transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-white/90' }}">
                    Home
                </a>
                <a href="{{ route('admin.users.index') }}" class="text-white font-medium hover:text-white/80 transition-colors {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.organizers.*') ? 'text-white' : 'text-white/90' }}">
                    Users
                </a>
                <a href="{{ route('admin.events.index') }}" class="text-white font-medium hover:text-white/80 transition-colors {{ request()->routeIs('admin.events.*') ? 'text-white' : 'text-white/90' }}">
                    Events
                </a>
                <a href="{{ route('admin.reports.index') }}" class="text-white font-medium hover:text-white/80 transition-colors {{ request()->routeIs('admin.reports.*') ? 'text-white' : 'text-white/90' }}">
                    Reports
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