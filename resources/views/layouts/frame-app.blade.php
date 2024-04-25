<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset("img/favicon/apple-icon-57x57.png") }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset("img/favicon/apple-icon-60x60.png") }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset("img/favicon/apple-icon-72x72.png") }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset("img/favicon/apple-icon-76x76.png") }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset("img/favicon/apple-icon-114x114.png") }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset("img/favicon/apple-icon-120x120.png") }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset("img/favicon/apple-icon-144x144.png") }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset("img/favicon/apple-icon-152x152.png") }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset("img/favicon/apple-icon-180x180.png") }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset("img/favicon/android-icon-192x192.png") }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset("img/favicon/favicon-32x32.png") }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset("img/favicon/favicon-96x96.png") }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset("img/favicon/favicon-16x16.png") }}">
    <link rel="manifest" href="{{ asset("img/favicon/manifest.json") }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset("img/favicon/ms-icon-144x144.png") }}">
    <meta name="theme-color" content="#ffffff">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    @vite('resources/css/app.css')

    @livewireStyles

    <!-- Scripts -->
    @vite('resources/js/app.js')
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js" defer></script>
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100">
{{--    @if(!request()->routeIs("app.login"))--}}
{{--        <div class="grid grid-cols-2 gap-4 p-4">--}}
{{--            @if(session()->has("token"))--}}
{{--                <a href="{{ route("app.user") }}">--}}
{{--                    <x-button class="w-full bg-red-800 hover:bg-red-700 justify-center">User</x-button>--}}
{{--                </a>--}}
{{--                <form method="POST" action="{{ route("app.logout") }}">--}}
{{--                    <x-button type="submit" class="w-full bg-red-800 hover:bg-red-700 justify-center">Logout</x-button>--}}
{{--                </form>--}}
{{--            @else--}}
{{--                <a href="{{ route("app.login") }}">--}}
{{--                    <x-button class="w-full bg-red-800 hover:bg-red-700 justify-center">Login</x-button>--}}
{{--                </a>--}}
{{--                <a href="{{ route("app.register") }}">--}}
{{--                    <x-button class="w-full bg-red-800 hover:bg-red-700 justify-center">Register</x-button>--}}
{{--                </a>--}}
{{--            @endif--}}
{{--        </div>--}}
{{--    @endif--}}

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

    <footer class="flex flex-col items-center justify-center bg-white">
        <div class="flex flex-col items-center justify-center py-4">
            <img class="w-12" src="{{ asset("img/myTables.svg") }}"/>
            <h5 class="font-bold text-red-800">myTables</h5>
        </div>
        <div class="bg-red-800 text-white py-4 w-full flex justify-center items-center">
            <p>&copy; {{ date("Y") }} myTables. All rights reserved.</p>
        </div>
    </footer>
</div>

@stack('modals')

@livewireScripts

@stack("scripts")
</body>
</html>
