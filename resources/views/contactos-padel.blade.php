<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Parejas de Padel - Badia Padel Tour</title>
    <link rel="icon" href="/favicon.png">
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/contacts-app/main.ts'])
    <style>
        :root {
            --electric: #c9ff00;
            --black: #0f0f0f;
        }

        body {
            margin: 0;
            font-family: 'Gopher', 'Inter', sans-serif;
            background: var(--black);
        }
    </style>
</head>
<body>
    @include('components.header')

    <main>
        <div id="contactos-padel-app"></div>
    </main>

    @include('components.footer')
</body>
</html>
