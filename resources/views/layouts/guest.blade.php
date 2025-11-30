<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Eventify' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-primary-600 flex flex-col">
        <!-- Logo -->
        <div class="p-8">
            <a href="{{ route('landing') }}">
                <img src="{{ asset('images/logo-white.svg') }}" alt="Eventify" class="h-8">
            </a>
        </div>

        <!-- Content -->
        <div class="flex-1 flex items-center justify-center px-4 pb-16">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <div class="py-6 text-center text-white/70 text-sm">
            &copy; {{ date('Y') }} &mdash; All rights reserved. Eventify
        </div>
    </div>
</body>
</html>