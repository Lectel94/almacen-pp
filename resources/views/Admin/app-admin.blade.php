<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'WareHouse_PP') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="icon" href="{{ asset('img/logo1.jpg') }}">
    <!-- Scripts -->

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="{{asset('/css/sweetalert2.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/sweetalert.min.css')}}">


    <!-- Styles -->

    @livewireStyles
</head>

<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-gray-100">
        @livewire('menu-admin')


        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-white shadow">
            <div class="px-2 py-6 mx-auto max-w-10xl sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @stack('modals')


    @livewireScripts

    @stack('js-livewire')

    <script type="text/javascript" src="{{asset('/js/sweetalert2.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/js/sweetalert.min.js')}}"></script>

    <!-- En tu plantilla Blade, en la etiqueta <head> -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>