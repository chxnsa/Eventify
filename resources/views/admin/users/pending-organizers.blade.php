<x-admin-layout>
    <x-slot name="title">Pending Organizers</x-slot>

    <!-- Back Button -->
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Users
    </a>

    <h1 class="text-2xl font-bold text-gray-900 mb-8">Pending Organizer Approvals</h1>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    @if($organizers->count() > 0)
        <div class="space-y-4">
            @foreach($organizers as $organizer)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-full bg-primary-100 flex items-center justify-center">
                                <span class="text-xl font-bold text-primary-600">{{ substr($organizer->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $organizer->name }}</h3>
                                <p class="text-gray-500">{{ $organizer->email }}</p>
                                <p class="text-sm text-gray-400">Phone: {{ $organizer->phone ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="text-sm text-gray-500">
                            Registered: {{ $organizer->created_at->format('d M Y, H:i') }}
                        </div>

                        <div class="flex gap-2">
                            <form action="{{ route('admin.organizers.approve', $organizer) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-primary text-sm">
                                    Approve
                                </button>
                            </form>
                            <button type="button" onclick="showRejectModal({{ $organizer->id }})" class="btn-danger text-sm">
                                Reject
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $organizers->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-xl shadow-sm">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">No pending approvals</h3>
            <p class="text-gray-500">All organizer applications have been processed.</p>
        </div>
    @endif

    <!-- Reject Modal -->
    <div id="reject-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Reject Organizer</h3>
            <form id="reject-form" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for rejection *</label>
                    <textarea 
                        name="rejection_reason" 
                        id="rejection_reason" 
                        rows="3" 
                        class="form-input"
                        placeholder="Please provide a reason..."
                        required
                    ></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="hideRejectModal()" class="btn-secondary flex-1">
                        Cancel
                    </button>
                    <button type="submit" class="btn-danger flex-1">
                        Reject
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function showRejectModal(userId) {
            document.getElementById('reject-form').action = '/admin/organizers/' + userId + '/reject';
            document.getElementById('reject-modal').classList.remove('hidden');
        }

        function hideRejectModal() {
            document.getElementById('reject-modal').classList.add('hidden');
        }
    </script>
    @endpush
</x-admin-layout>