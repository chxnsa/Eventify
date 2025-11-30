<x-admin-layout>
    <x-slot name="title">Edit User</x-slot>

    <!-- Back Button -->
    <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to User
    </a>

    <h1 class="text-2xl font-bold text-gray-900 mb-8">Edit User</h1>

    <div class="bg-white rounded-xl shadow-sm p-6 max-w-2xl">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $user->name) }}"
                    class="form-input"
                    required
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email', $user->email) }}"
                    class="form-input"
                    required
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone" 
                    value="{{ old('phone', $user->phone) }}"
                    class="form-input"
                >
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div class="mb-6">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                <select name="role" id="role" class="form-input" required>
                    <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                    <option value="organizer" {{ old('role', $user->role) === 'organizer' ? 'selected' : '' }}>Organizer</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">
                    Update User
                </button>
                <a href="{{ route('admin.users.show', $user) }}" class="btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>