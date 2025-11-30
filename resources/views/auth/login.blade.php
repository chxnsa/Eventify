<x-guest-layout>
    <x-slot name="title">Login</x-slot>

    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8">
        <h1 class="text-2xl font-bold text-gray-800 text-center mb-8">
            Login to your Account
        </h1>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 text-sm text-green-600 bg-green-50 p-3 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

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
                    autofocus
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                </div>
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

            <!-- Remember Me -->
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full btn-primary opacity-70 hover:opacity-100">
                Login
            </button>
        </form>

        <!-- Register Link -->
        <p class="mt-6 text-center text-sm text-gray-600">
            Don't have an Account? 
            <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                Register
            </a>
        </p>
    </div>
</x-guest-layout>