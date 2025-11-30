<x-app-layout>
    <x-slot name="title">Edit Profile</x-slot>

    <div class="max-w-3xl mx-auto px-4 py-8">
        <!-- Back Button -->
        <a href="{{ route('user.profile.show') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Profile
        </a>

        <h1 class="text-2xl font-bold text-gray-900 mb-8">Edit Profile</h1>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Avatar -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                    <div class="flex items-center gap-4">
                        <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover" id="avatar-preview">
                            @else
                                <span class="text-3xl font-bold text-gray-400" id="avatar-initial">{{ substr($user->name, 0, 1) }}</span>
                                <img src="" alt="" class="w-full h-full object-cover hidden" id="avatar-preview">
                            @endif
                        </div>
                        <div>
                            <input type="file" name="avatar" id="avatar" class="hidden" accept="image/*" onchange="previewAvatar(this)">
                            <label for="avatar" class="btn-secondary cursor-pointer">
                                Change Photo
                            </label>
                            <p class="text-xs text-gray-500 mt-1">JPG, JPEG, PNG. Max 2MB.</p>
                        </div>
                    </div>
                    @error('avatar')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
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
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
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
                <div class="mb-6">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
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

                <button type="submit" class="btn-primary">
                    Save Changes
                </button>
            </form>
        </div>

        <!-- Delete Account -->
        <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
            <h3 class="font-semibold text-red-600 mb-2">Delete Account</h3>
            <p class="text-sm text-gray-600 mb-4">
                Once your account is deleted, all of its resources and data will be permanently deleted.
            </p>
            <button type="button" onclick="showDeleteModal()" class="btn-danger">
                Delete Account
            </button>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-bold text-gray-900 mb-2">Delete Account</h3>
            <p class="text-gray-600 mb-4">
                Are you sure you want to delete your account? This action cannot be undone. Please enter your password to confirm.
            </p>
            
            <form action="{{ route('user.profile.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input"
                        required
                    >
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="hideDeleteModal()" class="btn-secondary flex-1">
                        Cancel
                    </button>
                    <button type="submit" class="btn-danger flex-1">
                        Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatar-preview');
                    const initial = document.getElementById('avatar-initial');
                    
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    
                    if (initial) {
                        initial.classList.add('hidden');
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function showDeleteModal() {
            document.getElementById('delete-modal').classList.remove('hidden');
        }

        function hideDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
        }
    </script>
    @endpush
</x-app-layout>