<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Application Rejected - Eventify</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col items-center justify-center p-4">
        <!-- Logo -->
        <a href="{{ route('landing') }}" class="mb-8">
            <img src="{{ asset('images/logo.svg') }}" alt="Eventify" class="h-10">
        </a>

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8 max-w-md w-full text-center">
            <!-- Status Badge -->
            <div class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-medium mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Application Rejected
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-4">
                Your Application Was Not Approved
            </h1>

            <p class="text-gray-600 mb-4">
                Unfortunately, we were unable to approve your organizer application at this time.
            </p>

            @if($user->rejection_reason)
                <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
                    <p class="text-sm text-gray-500 mb-1">Reason:</p>
                    <p class="text-gray-700">{{ $user->rejection_reason }}</p>
                </div>
            @endif

            <p class="text-gray-600 mb-8">
                If you believe this was a mistake or have additional information to provide, please contact our support team.
            </p>

            <!-- Actions -->
            <div class="space-y-3">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full btn-secondary">
                        Logout
                    </button>
                </form>

                <form action="{{ route('organizer.delete-account') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full text-red-600 hover:text-red-700 text-sm font-medium">
                        Delete My Account
                    </button>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-gray-500 text-sm mt-8">
            Need help? Contact us at support@eventify.com
        </p>
    </div>
</body>
</html>