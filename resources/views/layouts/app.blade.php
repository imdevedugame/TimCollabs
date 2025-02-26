<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts & Scripts -->
        <script src="//unpkg.com/alpinejs" defer></script>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js','resources/js/calendar.js'])
    </head>

    <body class="font-sans antialiased">
        <!-- Wrapper utama menggunakan display: flex -->
        <div class="w-full min-h-screen bg-gray-100 dark:bg-gray-900 flex">
            <!-- Sidebar: Livewire Component -->
            <livewire:layout.navigation />

            <!-- Konten Utama -->
           

        {{-- Tempatkan di luar <main> agar script bisa dipanggil setelah konten --}}
        @yield('scripts')
    </body>
</html>
