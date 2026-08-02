@extends('layouts-main.App')

@section('content')
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-gray-50">

        <x-auth.info />

        {{-- ================= RIGHT SIDE (LOGIN) ================= --}}
        <div class="flex items-center justify-center p-6 sm:p-10">

            <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8" x-data="{ email: '', password: '', showPassword: false, loading: false, rememberMe: false }">

                <x-auth.header />

                <x-auth.errors />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label class="text-sm text-gray-700 mb-1 block">البريد الإلكتروني</label>
                        <input type="email" name="email" x-model="email" value="{{ old('email') }}"
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:bg-white outline-none text-sm"
                            placeholder="example@clinic.com">
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="text-sm text-gray-700 mb-1 block">كلمة المرور</label>

                        <div class="relative">
                            <input type="password" name="password" x-model="password"
                                class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-emerald-500 focus:bg-white outline-none text-sm"
                                placeholder="••••••••">

                            <button type="button" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"
                                @click="showPassword = !showPassword">
                                <i :class="showPassword ? 'fa-eye-slash' : 'fa-eye'" class="fa-regular"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Remember --}}
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="remember" name="remember" x-model="rememberMe"
                            class="rounded border-gray-300 text-emerald-600">

                        <label for="remember" class="text-sm text-gray-600">
                            تذكرني
                        </label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-xl font-semibold transition">
                        تسجيل الدخول
                    </button>
                    <div class="flex items-center justify-between text-sm mt-2">
                        <a href="{{ route('password.request') }}"
                            class="text-emerald-600 hover:text-emerald-700 font-medium transition">
                            نسيت كلمة المرور؟
                        </a>

                        <a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-900 font-medium transition">
                            إنشاء حساب جديد
                        </a>
                    </div>
                </form>
                <x-auth.google/>
            </div>
        </div>
    </div>
@endsection
