<nav class="sticky top-0 z-50 px-4 py-4">
    <div class="max-w-7xl mx-auto">
        <div class="bg-primary-600 rounded-full px-6 py-3 flex items-center justify-between shadow-lg">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex-shrink-0">
                <img src="{{ asset('images/logo-white.svg') }}" alt="Eventify" class="h-7">
            </a>

            <!-- Search Bar (Desktop) -->
            <div class="hidden md:flex flex-1 max-w-2xl mx-8">
                <form action="{{ route('search') }}" method="GET" class="w-full">
                    <div class="flex items-center bg-white rounded-full px-4 py-2 w-full">
                        <!-- Search Input -->
                        <input 
                            type="text" 
                            name="q" 
                            placeholder="Search events..." 
                            value="{{ request('q') }}"
                            class="flex-1 border-none focus:ring-0 text-sm text-gray-700 placeholder-gray-400 bg-transparent"
                        >
                        
                        <span class="text-gray-300 mx-2">|</span>
                        
                        <!-- Location Dropdown -->
                        <div class="relative">
                            <select 
                                name="location" 
                                class="appearance-none border-none focus:ring-0 text-sm text-gray-700 bg-transparent pr-6 cursor-pointer"
                            >
                                <option value="">Location</option>
                                @foreach(config('locations.cities') as $city)
                                    <option value="{{ $city }}" {{ request('location') == $city ? 'selected' : '' }}>
                                        {{ $city }}
                                    </option>
                                @endforeach
                            </select>
                            
                        </div>
                        
                        <span class="text-gray-300 mx-2">|</span>
                        
                        <!-- Date Picker -->
                        <input 
                            type="date" 
                            name="date" 
                            value="{{ request('date') }}"
                            class="w-32 border-none focus:ring-0 text-sm text-gray-700 placeholder-gray-400 bg-transparent cursor-pointer"
                            min="{{ date('Y-m-d') }}"
                        >
                        
                        <!-- Search Button -->
                        <button type="submit" class="ml-2 bg-primary-600 text-white p-2 rounded-full hover:bg-primary-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Navigation Links -->
            <div class="flex items-center space-x-6">
                <a href="{{ route('home') }}" class="text-white font-medium hover:text-white/80 transition-colors {{ request()->routeIs('home') ? 'text-white' : 'text-white/90' }}">
                    Home
                </a>

                @auth
                    @if(auth()->user()->role === 'user')
                        <a href="{{ route('user.bookings.index') }}" class="text-white font-medium hover:text-white/80 transition-colors {{ request()->routeIs('user.bookings.*') ? 'text-white' : 'text-white/90' }}">
                            My Tickets
                        </a>
                        <a href="{{ route('user.favorites.index') }}" class="text-white font-medium hover:text-white/80 transition-colors {{ request()->routeIs('user.favorites.*') ? 'text-white' : 'text-white/90' }}">
                            Favorite
                        </a>
                        <a href="{{ route('user.profile.show') }}" class="text-white font-medium hover:text-white/80 transition-colors {{ request()->routeIs('user.profile.*') ? 'text-white' : 'text-white/90' }}">
                            Profile
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="text-white font-medium hover:text-white/80 transition-colors">
                        Register
                    </a>
                    <a href="{{ route('login') }}" class="text-white font-medium hover:text-white/80 transition-colors">
                        Login
                    </a>
                @endauth
            </div>

            <button type="button" class="md:hidden text-white ml-4" onclick="toggleMobileMenu()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden mt-2 bg-white rounded-2xl shadow-lg p-4">
            <form action="{{ route('search') }}" method="GET" class="mb-4 space-y-3">
                <input 
                    type="text" 
                    name="q" 
                    placeholder="Search events..." 
                    value="{{ request('q') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-full text-sm"
                >
                
                <select 
                    name="location" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-full text-sm text-gray-700"
                >
                    <option value="">Select Location</option>
                    @foreach(config('locations.cities') as $city)
                        <option value="{{ $city }}" {{ request('location') == $city ? 'selected' : '' }}>
                            {{ $city }}
                        </option>
                    @endforeach
                </select>

                <input 
                    type="date" 
                    name="date" 
                    value="{{ request('date') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-full text-sm text-gray-700"
                    min="{{ date('Y-m-d') }}"
                >

                <button type="submit" class="w-full bg-primary-600 text-white px-4 py-2 rounded-full hover:bg-primary-700 transition-colors">
                    Search
                </button>
            </form>
            
            <div class="space-y-2 pt-3 border-t border-gray-200">
                <a href="{{ route('home') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Home</a>
                @auth
                    @if(auth()->user()->role === 'user')
                        <a href="{{ route('user.bookings.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">My Tickets</a>
                        <a href="{{ route('user.favorites.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Favorite</a>
                        <a href="{{ route('user.profile.show') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Profile</a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Register</a>
                    <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Login</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    menu.classList.toggle('hidden');
}
</script>