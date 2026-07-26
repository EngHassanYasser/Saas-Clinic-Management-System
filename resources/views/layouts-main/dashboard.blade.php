<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'موعدي')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-layouts.header />
    <div class="flex min-h-screen" dir="rtl">
        <x-dashboards.sidebar />
        <main class="flex-1 overflow-x-hidden">
            @yield('content')
        </main>
    </div>
    <x-layouts.footer />
</body>
</html>
