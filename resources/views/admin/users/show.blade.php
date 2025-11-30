<x-admin-layout>
    <x-slot name="title">User Details</x-slot>

    <!-- Back Button -->
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Users
    </a>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Profile Card -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="bg-primary-600 p-6 text-center">
                <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center mx-auto mb-4 overflow-hidden">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-3xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                    @endif
                </div>
                <h2 class="text-xl font-bold text-white">{{ $user->name }}</h2>
                <p class="text-white/70">{{ $user->email }}</p>
            </div>

            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Role</span>
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
                    </div>

                    @if($user->role === 'organizer')
                        <div class="flex justify-between">
                            <span class="text-gray-500">Status</span>
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
                        </div>
                    @endif

                    <div class="flex justify-between">
                        <span class="text-gray-500">Phone</span>
                        <span class="text-gray-900">{{ $user->phone ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Joined</span>
                        <span class="text-gray-900">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.users.edit', $user) }}" class="w-full btn-primary block text-center">
                        Edit User
                    </a>
                </div>
            </div>
        </div>

        <!-- User Activity -->
        <div class="lg:col-span-2 space-y-6">
            @if($user->role === 'organizer')
                <!-- Organizer Events -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Events ({{ $user->events->count() }})</h3>
                    @if($user->events->count() > 0)
                        <div class="space-y-3">
                            @foreach($user->events->take(5) as $event)
                                <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $event->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $event->date_start->format('d M Y') }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $event->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No events created.</p>
                    @endif
                </div>
            @endif

            @if($user->role === 'user')
                <!-- User Bookings -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Bookings ({{ $user->bookings->count() }})</h3>
                    @if($user->bookings->count() > 0)
                        <div class="space-y-3">
                            @foreach($user->bookings->take(5) as $booking)
                                @php
                                    $event = $booking->details->first()->ticket->event ?? null;
                                @endphp
                                <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                                    <div>
                                        <p class="font-mono text-sm text-gray-900">{{ $booking->booking_code }}</p>
                                        <p class="text-sm text-gray-500">{{ $event ? $event->name : '-' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900">{{ $booking->formatted_total }}</p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $booking->status_color }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No bookings yet.</p>
                    @endif
                </div>

                <!-- User Reviews -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Reviews ({{ $user->reviews->count() }})</h3>
                    @if($user->reviews->count() > 0)
                        <div class="space-y-3">
                            @foreach($user->reviews->take(5) as $review)
                                <div class="py-2 border-b border-gray-50 last:border-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-sm text-gray-500">{{ $review->event->name }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600">{{ Str::limit($review->comment, 100) }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No reviews yet.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>