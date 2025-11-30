<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Eventify' }} - Eventify</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navbar -->
        @include('components.navbar')

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        @include('components.footer')
    </div>

   
    <script>
    function toggleFavorite(eventId, button) {
        console.log('Toggle favorite for event:', eventId);
        
        fetch(`/favorites/${eventId}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            const svg = button.querySelector('svg');
            const text = button.querySelector('.favorite-text');
            
            if (data.favorited) {
                svg.classList.remove('text-gray-400');
                svg.classList.add('text-red-500');
                svg.setAttribute('fill', 'currentColor');
                if (text) text.textContent = 'Saved to favorites';
            } else {
                svg.classList.remove('text-red-500');
                svg.classList.add('text-gray-400');
                svg.setAttribute('fill', 'none');
                if (text) text.textContent = 'Add to favorites';
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>

@stack('scripts')
</body>

</html>