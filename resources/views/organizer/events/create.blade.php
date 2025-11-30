<x-organizer-layout>
    <x-slot name="title">Create Event</x-slot>

    <!-- Back Button -->
    <a href="{{ route('organizer.events.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Events
    </a>

    <h1 class="text-2xl font-bold text-gray-900 mb-8">Create New Event</h1>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('organizer.events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Event Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Event Name *</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}"
                        class="form-input"
                        required
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category_id" id="category_id" class="form-input" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" id="status" class="form-input" required>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date Start -->
                <div>
                    <label for="date_start" class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                    <input 
                        type="date" 
                        id="date_start" 
                        name="date_start" 
                        value="{{ old('date_start') }}"
                        class="form-input"
                        required
                    >
                    @error('date_start')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date End -->
                <div>
                    <label for="date_end" class="block text-sm font-medium text-gray-700 mb-2">End Date (Optional)</label>
                    <input 
                        type="date" 
                        id="date_end" 
                        name="date_end" 
                        value="{{ old('date_end') }}"
                        class="form-input"
                    >
                    @error('date_end')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Time Start -->
                <div>
                    <label for="time_start" class="block text-sm font-medium text-gray-700 mb-2">Start Time *</label>
                    <input 
                        type="time" 
                        id="time_start" 
                        name="time_start" 
                        value="{{ old('time_start') }}"
                        class="form-input"
                        required
                    >
                    @error('time_start')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Time End -->
                <div>
                    <label for="time_end" class="block text-sm font-medium text-gray-700 mb-2">End Time *</label>
                    <input 
                        type="time" 
                        id="time_end" 
                        name="time_end" 
                        value="{{ old('time_end') }}"
                        class="form-input"
                        required
                    >
                    @error('time_end')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">City/Location *</label>
                    <input 
                        type="text" 
                        id="location" 
                        name="location" 
                        value="{{ old('location') }}"
                        placeholder="e.g. Makassar"
                        class="form-input"
                        required
                    >
                    @error('location')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Venue -->
                <div>
                    <label for="venue" class="block text-sm font-medium text-gray-700 mb-2">Venue *</label>
                    <input 
                        type="text" 
                        id="venue" 
                        name="venue" 
                        value="{{ old('venue') }}"
                        placeholder="e.g. Convention Center"
                        class="form-input"
                        required
                    >
                    @error('venue')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Full Address</label>
                    <input 
                        type="text" 
                        id="address" 
                        name="address" 
                        value="{{ old('address') }}"
                        placeholder="Full address (optional)"
                        class="form-input"
                    >
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="5"
                        class="form-input"
                        placeholder="Describe your event..."
                        required
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Event Image</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <input type="file" name="image" id="image" class="hidden" accept="image/*" onchange="previewImage(this)">
                        <img id="image-preview" src="" alt="" class="max-h-48 mx-auto mb-4 hidden">
                        <label for="image" class="cursor-pointer">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-primary-600 font-medium">Click to upload</span>
                            <span class="text-gray-500"> or drag and drop</span>
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 2MB</p>
                        </label>
                    </div>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="submit" class="btn-primary">
                    Create Event
                </button>
                <a href="{{ route('organizer.events.index') }}" class="btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('image-preview');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    @endpush
</x-organizer-layout>