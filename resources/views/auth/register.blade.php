<x-guest-layout>
    <x-slot name="title">Register</x-slot>

    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8">
        <h1 class="text-2xl font-bold text-gray-800 text-center mb-8">
            Create your Account
        </h1>

        <form method="POST" action="{{ route('register') }}">
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
                Register
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            Already have an Account? 
            <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                Login
            </a>
        </p>
        <p class="mt-2 text-center text-sm text-gray-600">
            Want to become organizer instead? 
            <a href="{{ route('register.organizer') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                Register as Organizer
            </a>
        </p>
    </div>
</x-guest-layout>