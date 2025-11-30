<x-app-layout>
    <x-slot name="title">Profile</x-slot>

    <div class="max-w-3xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-8">Profile</h1>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <!-- Profile Header -->
            <div class="bg-primary-600 p-6">
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center overflow-hidden">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-3xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">{{ $user->name }}</h2>
                        <p class="text-white/70">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Account Information</h3>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100">
                        <span class="text-gray-500">Full Name</span>
                        <span class="col-span-2 text-gray-900">{{ $user->name }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100">
                        <span class="text-gray-500">Email</span>
                        <span class="col-span-2 text-gray-900">{{ $user->email }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100">
                        <span class="text-gray-500">Phone</span>
                        <span class="col-span-2 text-gray-900">{{ $user->phone ?? '-' }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-4 py-3">
                        <span class="text-gray-500">Member Since</span>
                        <span class="col-span-2 text-gray-900">{{ $user->created_at->format('d F Y') }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-wrap gap-3 mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('user.profile.edit') }}" class="btn-primary">
                        Edit Profile
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn-secondary">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>