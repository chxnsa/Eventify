<x-admin-layout>
    <x-slot name="title">Users Management</x-slot>

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Users Management</h1>
        <a href="{{ route('admin.organizers.pending') }}" class="btn-secondary">
            Pending Organizers
        </a>
    </div>

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

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Search name or email..."
                    class="form-input"
                >
            </div>
            <select name="role" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="organizer" {{ request('role') === 'organizer' ? 'selected' : '' }}>Organizer</option>
                <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
            </select>
            <select name="organizer_status" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Status</option>
                <option value="pending" {{ request('organizer_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('organizer_status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('organizer_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <button type="submit" class="btn-secondary">Filter</button>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center overflow-hidden">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-primary-600 font-semibold">{{ substr($user->name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @php
                                    $roleColors = [
                                        'admin' => 'bg-red-100 text-red-800',
                                        'organizer' => 'bg-purple-100 text-purple-800',
                                        'user' => 'bg-blue-100 text-blue-800',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleColors[$user->role] }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                @if($user->role === 'organizer')
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'approved' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$user->organizer_status] }}">
                                        {{ ucfirst($user->organizer_status) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-primary-600 hover:text-primary-700" title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-yellow-600 hover:text-yellow-700" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-700" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $users->withQueryString()->links() }}
    </div>
</x-admin-layout>