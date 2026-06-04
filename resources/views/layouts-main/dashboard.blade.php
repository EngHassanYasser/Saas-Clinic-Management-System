<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'موعدي')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <x-layouts.header />
    <div class="flex min-h-screen" dir="rtl">
        <x-dashboard.sidebar />

        {{-- =====================<!-- Main Content -->===================== --}}
        <main class="flex-1 overflow-x-hidden">

            @yield('content')
        </main>
    </div>
    <x-layouts.footer />

</body>

</html>
