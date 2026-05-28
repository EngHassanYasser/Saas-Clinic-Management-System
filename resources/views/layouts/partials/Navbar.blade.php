   <!-- Navbar -->
    <header class="bg-white shadow-sm border-b sticky top-0 z-50">

        <div class="max-w-7xl mx-auto px-6">

            <div class="flex items-center justify-between h-20">

                <!-- Logo -->
                <a
                    href="/"
                    class="flex items-center gap-3"
                >

                    <div class="w-12 h-12 rounded-2xl bg-emerald-600 flex items-center justify-center text-white text-2xl font-black">
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

                    <a
                        href="#"
                        class="text-gray-700 hover:text-emerald-600 transition font-semibold"
                    >
                        الرئيسية
                    </a>

                    <a
                        href="#"
                        class="text-gray-700 hover:text-emerald-600 transition font-semibold"
                    >
                        العيادات
                    </a>

                    <a
                        href="#"
                        class="text-gray-700 hover:text-emerald-600 transition font-semibold"
                    >
                        التخصصات
                    </a>

                    <a
                        href="#"
                        class="text-gray-700 hover:text-emerald-600 transition font-semibold"
                    >
                        الأطباء
                    </a>

                    <a
                        href="#"
                        class="text-gray-700 hover:text-emerald-600 transition font-semibold"
                    >
                        من نحن
                    </a>

                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-4">

                    <a
                        href="{{ route('login') }}"
                        class="hidden md:flex text-gray-700 hover:text-emerald-600 transition font-semibold"
                    >
                        تسجيل الدخول
                    </a>

                    <a
                        href="{{ route('logout') }}"
                        class="bg-emerald-600 hover:bg-emerald-700 transition text-white px-6 py-3 rounded-2xl font-bold"
                    >
                        إنشاء حساب
                    </a>

                </div>

            </div>

        </div>

    </header>