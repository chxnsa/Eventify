<x-admin-layout>
    <x-slot name="title">Edit Event</x-slot>

    <!-- Back Button -->
    <a href="{{ route('admin.events.show', $event) }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Event
    </a>

    <h1 class="text-2xl font-bold text-gray-900 mb-8">Edit Event</h1>

    <div class="bg-white rounded-xl shadow-sm p-6 max-w-2xl">
        <form action="{{ route('admin.events.update', $event) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Event Name *</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $event->name) }}"
                    class="form-input"
                    required
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                <select name="category_id" id="category_id" class="form-input" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="status" id="status" class="form-input" required>
                    <option value="draft" {{ old('status', $event->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $event->status) === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="cancelled" {{ old('status', $event->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Featured -->
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $event->is_featured) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                    <span class="ml-2 text-sm text-gray-700">Featured Event</span>
                </label>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">
                    Update Event
                </button>
                <a href="{{ route('admin.events.show', $event) }}" class="btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>