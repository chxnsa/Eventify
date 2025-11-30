<x-guest-layout>
    <x-slot name="title">Register as Organizer</x-slot>

    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8">
        <h1 class="text-2xl font-bold text-gray-800 text-center mb-2">
            Become an Organizer
        </h1>
        <p class="text-gray-600 text-center text-sm mb-8">
            Create and manage your own events on Eventify
        </p>

        <form method="POST" action="{{ route('register.organizer') }}">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
                    class="form-input"
                    required 
                    autofocus
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    class="form-input"
                    required
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone" 
                    value="{{ old('phone') }}"
                    class="form-input"
                    required
                >
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input"
                    required
                >
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    class="form-input"
                    required
                >
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full btn-primary opacity-70 hover:opacity-100">
                Register as Organizer
            </button>
        </form>

        <!-- Login Link -->
        <p class="mt-6 text-center text-sm text-gray-600">
            Already have an Account? 
            <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                Login
            </a>
        </p>

        <!-- User Register Link -->
        <p class="mt-2 text-center text-sm text-gray-600">
            Want to book events instead? 
            <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                Register as User
            </a>
        </p>
    </div>
</x-guest-layout>