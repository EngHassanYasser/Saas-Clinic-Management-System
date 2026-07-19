<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'موعدي')</title>
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-gray-50 font-[Cairo]">
    <x-layouts.header />
    <!-- Main Content -->
    <main> @yield('content')
    </main>
    <x-layouts.footer />
</body>

</html>
