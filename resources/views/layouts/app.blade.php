<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack("meta")

    <title>@if(!empty($header)){{ strip_tags($header) }} - @endif{{ config('app.name', 'Laravel') }}</title>

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
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.key') }}"></script>

    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '598696100547055');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=598696100547055&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GMTCTNJYKX"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-GMTCTNJYKX');
    </script>
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100">
    @livewire('navigation-menu')

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
        <div class="container mx-auto grid grid-cols-2 py-5 px-3">
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
        <div class="flex flex-col space-y-2 justify-center items-center border-t border-white py-3">
            <p>&copy; {{ date("Y") }} myTables. All rights reserved.</p>
            <p>powered by <a class="hover:text-gray-300" href="https://str94.co.uk">STR94</a></p>
        </div>
    </footer>

    @auth
        @livewire("notifications.panel")
    @endif
</div>

@stack('modals')

@livewireScripts

@stack("scripts")
</body>
</html>
