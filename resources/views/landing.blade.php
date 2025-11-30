<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Eventify - Discover & Book Events Around You</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Custom Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-40px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(40px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        @keyframes pulse-slow {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }

        @keyframes slideInFromBottom {
            from {
                opacity: 0;
                transform: translateY(100px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .animate-fade-in-left {
            animation: fadeInLeft 0.8s ease-out forwards;
        }

        .animate-fade-in-right {
            animation: fadeInRight 0.8s ease-out forwards;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-pulse-slow {
            animation: pulse-slow 3s ease-in-out infinite;
        }

        .animate-slide-in-bottom {
            animation: slideInFromBottom 0.8s ease-out forwards;
        }

        .animation-delay-200 {
            animation-delay: 0.2s;
        }

        .animation-delay-400 {
            animation-delay: 0.4s;
        }

        .animation-delay-600 {
            animation-delay: 0.6s;
        }

        .animation-delay-800 {
            animation-delay: 0.8s;
        }

        /* Initial state for animations */
        .animate-on-scroll {
            opacity: 0;
        }

        .animate-on-scroll.animated {
            opacity: 1;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Hero Section -->
    <section class="min-h-screen bg-black relative overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute top-0 right-0 w-64 h-64 opacity-20 animate-float">
            <img src="{{ asset('images/decoration-curl.svg') }}" alt="" class="w-full h-full">
        </div>
        <div class="absolute bottom-0 left-0 w-48 h-48 opacity-30 animate-pulse-slow">
            <img src="{{ asset('images/decoration-flower.svg') }}" alt="" class="w-full h-full">
        </div>

        <!-- Navbar -->
        <nav class="px-4 py-6 relative z-10">
            <div class="max-w-7xl mx-auto">
                <div class="bg-primary-600 rounded-full px-6 py-3 flex items-center justify-between shadow-lg animate-fade-in-up">
                    <!-- Logo -->
                    <a href="{{ route('landing') }}" class="flex-shrink-0">
                        <img src="{{ asset('images/logo-white.svg') }}" alt="Eventify" class="h-7">
                    </a>

                    <!-- Navigation Links -->
                    <div class="flex items-center space-x-6">
                        <a href="{{ route('register') }}" class="text-white font-medium hover:text-white/80 transition-colors">
                            Register
                        </a>
                        <a href="{{ route('login') }}" class="text-white font-medium hover:text-white/80 transition-colors">
                            Login
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Content -->
        <div class="max-w-7xl mx-auto px-4 pt-12 pb-24">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="space-y-8">
                    <h1 class="text-5xl lg:text-6xl font-bold text-white leading-tight opacity-0 animate-fade-in-left" style="animation-delay: 0.2s; animation-fill-mode: forwards;">
                        Discover & book<br>
                        <span class="text-primary-600 italic font-bold">Events</span> around you.
                    </h1>

                    <a 
                        href="{{ route('home') }}" 
                        class="inline-flex items-center bg-primary-600 text-white px-8 py-4 rounded-full font-semibold hover:bg-primary-700 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 opacity-0 animate-fade-in-left"
                        style="animation-delay: 0.4s; animation-fill-mode: forwards;"
                    >
                        Explore Events
                    </a>
                </div>

                <!-- Right Content - Image -->
                <div class="relative opacity-0 animate-fade-in-right" style="animation-delay: 0.3s; animation-fill-mode: forwards;">
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl">
                        <img 
                            src="{{ asset('images/hero-concert.jpg') }}" 
                            alt="Concert" 
                            class="w-full h-[400px] object-cover"
                        >
                      
                    </div>
                    <!-- Decorative curl -->
                    <div class="absolute -right-8 -top-8 w-24 h-24 text-primary-600 opacity-50 animate-float" style="animation-delay: 1s;">
                        <img src="{{ asset('images/decoration-curl.svg') }}" alt="" class="w-full h-full">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tagline Section -->
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <p class="text-2xl lg:text-3xl text-gray-800 leading-relaxed opacity-0 animate-on-scroll animate-fade-in-up">
                Your all-in-one platform for 
                <span class="underline decoration-primary-600 decoration-2 underline-offset-4 font-bold">exploring events</span>, 
                <span class="underline decoration-primary-600 decoration-2 underline-offset-4 font-bold">booking tickets</span>, and 
                <span class="underline decoration-primary-600 decoration-2 underline-offset-4 font-bold">managing your own event</span> 
                in a simple and secure way.
            </p>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-20 bg-primary-600 relative overflow-hidden">
        <!-- Decorative Elements -->
        <div class="absolute top-0 right-0 w-48 h-48 opacity-10 animate-float">
            <img src="{{ asset('images/decoration-curl.svg') }}" alt="" class="w-full h-full">
        </div>
        <div class="absolute bottom-0 left-0 w-32 h-32 opacity-20 animate-pulse-slow">
            <img src="{{ asset('images/decoration-flower.svg') }}" alt="" class="w-full h-full">
        </div>

        <div class="max-w-5xl mx-auto px-4 relative z-10">
            <h2 class="text-3xl lg:text-4xl font-bold text-white text-center mb-12 opacity-0 animate-on-scroll animate-fade-in-up">
                EXPLORE CATEGORIES
            </h2>

            <div class="flex flex-wrap justify-center gap-4">
                @php
                    $categories = [
                        'Concerts & Music',
                        'Workshops',
                        'Seminars',
                        'Family & Kids',
                        'Festival',
                        'Sports & Fitness',
                        'Art & Exhibition',
                    ];
                @endphp

                @foreach($categories as $index => $category)
                    <a 
                        href="{{ route('home') }}?category={{ Str::slug($category) }}" 
                        class="px-6 py-3 bg-white/10 border border-white/30 rounded-full text-white font-medium hover:bg-white hover:text-primary-600 transition-all duration-300 opacity-0 animate-on-scroll animate-fade-in-up"
                        style="animation-delay: {{ ($index * 0.1) }}s; animation-fill-mode: forwards;"
                    >
                        {{ $category }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-black relative overflow-hidden">
        <!-- Decorative Element -->
        <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-32 h-32 opacity-30">
            <img src="{{ asset('images/decoration-flower-white.svg') }}" alt="" class="w-full h-full">
        </div>

        <div class="max-w-7xl mx-auto px-4">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="space-y-6 opacity-0 animate-on-scroll animate-fade-in-left">
                    <h2 class="text-4xl lg:text-5xl font-bold text-white leading-tight">
                        Start Your Event<br>Journey Today
                    </h2>
                    <p class="text-white/70 text-lg">
                        Your all-in-one platform for exploring events, booking tickets, and managing your own event in a simple and secure way.
                    </p>
                </div>

                <!-- Right Content - Button -->
                <div class="flex lg:justify-end opacity-0 animate-on-scroll animate-fade-in-right">
                    <a 
                        href="{{ route('home') }}" 
                        class="inline-flex items-center bg-white text-gray-900 px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 group"
                    >
                        <svg class="w-5 h-5 mr-3 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                        Explore Events
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Partner Section (for Organizers) -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <div class="opacity-0 animate-on-scroll animate-fade-in-up">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">
                    Become an Event Organizer
                </h2>
                <p class="text-lg text-gray-600 mb-8">
                    Partner with us to create events, track ticket sales, and grow your audience. 
                    Join thousands of successful organizers on Eventify.
                </p>
                <a 
                    href="{{ route('register.organizer') }}" 
                    class="inline-flex items-center bg-primary-600 text-white px-8 py-4 rounded-full font-semibold hover:bg-primary-700 transition-all duration-300 hover:shadow-lg hover:-translate-y-1"
                >
                    Register as Organizer
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black text-white py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-white/70 text-sm">
                &copy; {{ date('Y') }} &mdash; All rights reserved. Eventify
            </p>
        </div>
    </footer>

    <!-- Scroll Animation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Intersection Observer for scroll animations
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated');
                        // Get animation class from element
                        const animationClass = entry.target.dataset.animation || 'animate-fade-in-up';
                        entry.target.style.animationPlayState = 'running';
                    }
                });
            }, observerOptions);

            // Observe all elements with animate-on-scroll class
            document.querySelectorAll('.animate-on-scroll').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>