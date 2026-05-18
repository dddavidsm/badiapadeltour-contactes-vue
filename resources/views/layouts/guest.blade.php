<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BPT | Badia Padel Tour') }}</title>
        <link rel="icon" href="/favicon.png">
        <!-- Fonts Gopher -->
        <style>
            @font-face {
                font-family: 'Gopher';
                src: url('/fonts/Gopher/Gopher-Regular.ttf') format('truetype');
                font-weight: normal;
                font-style: normal;
            }
            @font-face {
                font-family: 'Gopher';
                src: url('/fonts/Gopher/Gopher-Bold.ttf') format('truetype');
                font-weight: bold;
                font-style: normal;
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Gopher', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased" style="background-color: #111111;">
        <div class="min-h-screen flex flex-col sm:justify-center items-center py-12 px-4">
            <div class="mb-10">
                <a href="/">
                    <img src="/assets/logo_electriclime.svg" alt="Badia Padel Tour" class="h-20 w-auto">
                </a>
            </div>

            <div class="w-full sm:max-w-xl px-14 py-14 shadow-2xl overflow-hidden rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
