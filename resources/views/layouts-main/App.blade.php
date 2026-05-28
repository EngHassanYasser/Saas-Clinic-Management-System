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

<body class="bg-gray-50 font-[Cairo]">

    <!-- Navbar -->
    <header class="bg-white shadow-sm border-b sticky top-0 z-50">

        <div class="max-w-7xl mx-auto px-6">

            <div class="flex items-center justify-between h-20">

                <!-- Logo -->
                <a href="/" class="flex items-center gap-3">

                    <div
                        class="w-12 h-12 rounded-2xl bg-emerald-600 flex items-center justify-center text-white text-2xl font-black">
                        م
                    </div>

                    <div>
                        <h1 class="text-2xl font-black text-gray-900">
                            موعدي
                        </h1>

                        <p class="text-xs text-gray-400">
                            Medical Booking Platform
                        </p>
                    </div>

                </a>

                <!-- Navigation -->
                <nav class="hidden lg:flex items-center gap-8">

                    <a href="#" class="text-gray-700 hover:text-emerald-600 transition font-semibold">
                        الرئيسية
                    </a>

                    <a href="#" class="text-gray-700 hover:text-emerald-600 transition font-semibold">
                        العيادات
                    </a>

                    <a href="#" class="text-gray-700 hover:text-emerald-600 transition font-semibold">
                        التخصصات
                    </a>

                    <a href="#" class="text-gray-700 hover:text-emerald-600 transition font-semibold">
                        الأطباء
                    </a>

                    <a href="#" class="text-gray-700 hover:text-emerald-600 transition font-semibold">
                        من نحن
                    </a>

                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="bg-emerald-600 hover:bg-emerald-700 transition text-white px-3 py-2 rounded-2xl font-bold">
                            لوحة التحكم
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 transition text-white px-3 py-2 rounded-2xl font-bold">
                                تسجيل الخروج
                            </button>
                        </form>
                    @endauth

                    @guest
                        <a href="{{ route('login') }}"
                            class="hidden md:flex text-gray-700 hover:text-emerald-600 transition font-semibold">
                            تسجيل الدخول
                        </a>

                        <a href="{{ route('register') }}"
                            class="bg-emerald-600 hover:bg-emerald-700 transition text-white px-6 py-3 rounded-2xl font-bold">
                            إنشاء حساب
                        </a>
                    @endguest
                </div>

            </div>

        </div>

    </header>

    <!-- Main Content -->
    <main>

        @yield('content')

    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-20 pb-10">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-10 mb-16">

                <!-- About -->
                <div>

                    <div class="flex items-center gap-3 mb-6">

                        <div
                            class="w-12 h-12 rounded-2xl bg-emerald-600 flex items-center justify-center text-white text-2xl font-black">
                            م
                        </div>

                        <div>
                            <h2 class="text-2xl font-black">
                                موعدي
                            </h2>

                            <p class="text-sm text-gray-400">
                                منصة حجز المواعيد الطبية
                            </p>
                        </div>

                    </div>

                    <p class="text-gray-400 leading-8">
                        منصة ذكية تساعد المرضى على حجز المواعيد الطبية بسهولة مع أفضل العيادات والأطباء في الكويت.
                    </p>

                </div>

                <!-- Quick Links -->
                <div>

                    <h3 class="text-xl font-black mb-6">
                        روابط سريعة
                    </h3>

                    <ul class="space-y-4 text-gray-400">

                        <li>
                            <a href="#" class="hover:text-white transition">
                                الرئيسية
                            </a>
                        </li>

                        <li>
                            <a href="#" class="hover:text-white transition">
                                العيادات
                            </a>
                        </li>

                        <li>
                            <a href="#" class="hover:text-white transition">
                                الأطباء
                            </a>
                        </li>

                        <li>
                            <a href="#" class="hover:text-white transition">
                                التخصصات
                            </a>
                        </li>

                    </ul>

                </div>

                <!-- Support -->
                <div>

                    <h3 class="text-xl font-black mb-6">
                        الدعم
                    </h3>

                    <ul class="space-y-4 text-gray-400">

                        <li>
                            <a href="#" class="hover:text-white transition">
                                تواصل معنا
                            </a>
                        </li>

                        <li>
                            <a href="#" class="hover:text-white transition">
                                الشروط والأحكام
                            </a>
                        </li>

                        <li>
                            <a href="#" class="hover:text-white transition">
                                سياسة الخصوصية
                            </a>
                        </li>

                        <li>
                            <a href="#" class="hover:text-white transition">
                                الأسئلة الشائعة
                            </a>
                        </li>

                    </ul>

                </div>

                <!-- Contact -->
                <div>

                    <h3 class="text-xl font-black mb-6">
                        معلومات التواصل
                    </h3>

                    <ul class="space-y-5 text-gray-400">

                        <li class="flex items-center gap-3">
                            <span>📍</span>
                            <span>الكويت - السالمية</span>
                        </li>

                        <li class="flex items-center gap-3">
                            <span>📞</span>
                            <span>+965 50000000</span>
                        </li>

                        <li class="flex items-center gap-3">
                            <span>✉️</span>
                            <span>info@maw3edy.com</span>
                        </li>

                    </ul>

                </div>

            </div>

            <!-- Bottom Footer -->
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row items-center justify-between gap-5">

                <p class="text-gray-500 text-sm">
                    © {{ date('Y') }} موعدي - جميع الحقوق محفوظة
                </p>

                <div class="flex items-center gap-4 text-2xl">

                    <a href="#" class="hover:text-emerald-400 transition">
                        🌐
                    </a>

                    <a href="#" class="hover:text-emerald-400 transition">
                        📘
                    </a>

                    <a href="#" class="hover:text-emerald-400 transition">
                        📷
                    </a>

                    <a href="#" class="hover:text-emerald-400 transition">
                        🐦
                    </a>

                </div>

            </div>

        </div>

    </footer>
</body>

</html>
