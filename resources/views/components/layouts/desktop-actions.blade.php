<!-- Desktop Actions -->
<div class="hidden lg:flex items-center gap-2">
    @auth
        <a href="{{ route('dashboard') }}"
            class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-2xl font-bold transition">
            لوحة التحكم
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-2xl font-bold transition">
                خروج
            </button>
        </form>
    @endauth
    @guest
        <a href="{{ route('login') }}" class="text-gray-700 hover:text-emerald-600 px-3 py-2 font-semibold transition">
            دخول
        </a>
        <a href="{{ route('register') }}"
            class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-3 rounded-2xl font-bold transition">
            حساب جديد
        </a>
    @endguest
</div>
