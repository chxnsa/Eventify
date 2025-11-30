<x-organizer-layout>
    <x-slot name="title">Edit Ticket</x-slot>

    <!-- Back Button -->
    <a href="{{ route('organizer.events.show', $event) }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Event
    </a>

    <h1 class="text-2xl font-bold text-gray-900 mb-2">Edit Ticket</h1>
    <p class="text-gray-600 mb-8">{{ $event->name }}</p>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('organizer.tickets.update', [$event, $ticket]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Ticket Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Ticket Name *</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $ticket->name) }}"
                        class="form-input"
                        required
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (Rp) *</label>
                    <input 
                        type="number" 
                        id="price" 
                        name="price" 
                        value="{{ old('price', $ticket->price) }}"
                        min="0"
                        step="1000"
                        class="form-input"
                        required
                    >
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quota -->
                <div>
                    <label for="quota" class="block text-sm font-medium text-gray-700 mb-2">Quota *</label>
                    <input 
                        type="number" 
                        id="quota" 
                        name="quota" 
                        value="{{ old('quota', $ticket->quota) }}"
                        min="{{ $ticket->sold }}"
                        class="form-input"
                        required
                    >
                    <p class="text-xs text-gray-500 mt-1">Minimum: {{ $ticket->sold }} (already sold)</p>
                    @error('quota')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sale Start -->
                <div>
                    <label for="sale_start" class="block text-sm font-medium text-gray-700 mb-2">Sale Start *</label>
                    <input 
                        type="datetime-local" 
                        id="sale_start" 
                        name="sale_start" 
                        value="{{ old('sale_start', $ticket->sale_start->format('Y-m-d\TH:i')) }}"
                        class="form-input"
                        required
                    >
                    @error('sale_start')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sale End -->
                <div>
                    <label for="sale_end" class="block text-sm font-medium text-gray-700 mb-2">Sale End *</label>
                    <input 
                        type="datetime-local" 
                        id="sale_end" 
                        name="sale_end" 
                        value="{{ old('sale_end', $ticket->sale_end->format('Y-m-d\TH:i')) }}"
                        class="form-input"
                        required
                    >
                    @error('sale_end')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="3"
                        class="form-input"
                    >{{ old('description', $ticket->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Benefits -->
                <div class="md:col-span-2">
                    <label for="benefits" class="block text-sm font-medium text-gray-700 mb-2">Benefits</label>
                    <textarea 
                        id="benefits" 
                        name="benefits" 
                        rows="3"
                        class="form-input"
                        placeholder="Enter benefits (one per line)"
                    >{{ old('benefits', $ticket->benefits) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Enter each benefit on a new line</p>
                    @error('benefits')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="submit" class="btn-primary">
                    Update Ticket
                </button>
                <a href="{{ route('organizer.events.show', $event) }}" class="btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-organizer-layout>