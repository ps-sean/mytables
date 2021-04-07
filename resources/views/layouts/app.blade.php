<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    @livewireStyles

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js" defer></script>
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100">
    @livewire('navigation-dropdown')

@if(!empty($header))
    <!-- Page Heading -->
        <header class="bg-white shadow relative z-10">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
@endif

<!-- Page Content -->
    <main>
        {{ $slot }}
    </main>

    <footer class="bg-red-800 text-white">
        <div class="container mx-auto grid grid-cols-2 py-5">
            <div class="space-y-2">
                <h5 class="text-lg font-bold mb-3">Popular Locations</h5>
                <p><a class="hover:text-gray-300" href="{{ route("home") }}?search=Livingston">Livingston</a></p>
            </div>
            <div class="space-y-2">
                <h5 class="text-lg font-bold mb-3">Who Are We?</h5>
                <p><a class="hover:text-gray-300" href="{{ route("restaurant-sign-up") }}">Restaurant Sign Up</a></p>
                <p><a class="hover:text-gray-300" href="{{ route("about-us") }}">About Us</a></p>
                <p><a class="hover:text-gray-300" href="{{ route("contact") }}">Contact Us</a></p>
            </div>
        </div>
    </footer>

    @auth
        @livewire("notifications.panel")
    @endif
</div>

@stack('modals')

@livewireScripts
</body>
</html>
