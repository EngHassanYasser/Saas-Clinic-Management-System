@extends('layouts-main.App')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-gray-50 px-4">

        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

            {{-- HEADER --}}
            <div class="p-6 border-b bg-white">
                <h1 class="text-2xl font-bold text-gray-900">إنشاء حساب جديد</h1>
                <p class="text-sm text-gray-500 mt-1">
                    سجل كـ مريض أو عيادة لإدارة خدماتك الطبية
                </p>
            </div>

            {{-- FORM --}}
            <div class="p-6">
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    {{-- Name --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700">الاسم الكامل</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full mt-2 px-4 py-3 border rounded-xl
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                        transition outline-none"
                            placeholder="مثال: Ahmed Ali" required autofocus>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- user name --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700">أسم المستخدم </label>
                        <input type="text" name="user_name" value="{{ old('user_name') }}"
                            class="w-full mt-2 px-4 py-3 border rounded-xl
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                        transition outline-none"
                            placeholder="مثال: Ahmed-Ali12" required autofocus>
                        @error('user_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Speciality --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700">التخصص</label>

                        <div class="relative mt-2" x-data="{ open: false, selected: '', selectedId: '{{ old('speciality_id') }}' }">

                            {{-- Hidden input للـ form --}}
                            <input type="hidden" name="speciality_id" :value="selectedId">

                            {{-- Trigger Button --}}
                            <button type="button" @click="open = !open"
                                class="w-full px-4 py-3 border rounded-xl text-right bg-white
                   flex items-center justify-between
                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                   transition outline-none"
                                :class="selectedId ? 'text-gray-900' : 'text-gray-400'">
                                <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                                <span x-text="selected || 'اختر التخصص'"></span>
                            </button>

                           <x-specialities :$specialities/>
                    {{-- Gender --}}
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            النوع
                        </label>

                        <div class="flex items-center gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="gender" value="male"
                                    {{ old('gender') == 'male' ? 'checked' : '' }}
                                    class="text-blue-600 focus:ring-blue-500">
                                <span>ذكر</span>
                            </label>

                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="gender" value="female"
                                    {{ old('gender') == 'female' ? 'checked' : '' }}
                                    class="text-blue-600 focus:ring-blue-500">
                                <span>أنثى</span>
                            </label>
                        </div>

                        @error('gender')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full mt-2 px-4 py-3 border rounded-xl
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                        transition outline-none"
                            placeholder="name@email.com" required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700">كلمة المرور</label>
                        <input type="password" name="password"
                            class="w-full mt-2 px-4 py-3 border rounded-xl
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                        transition outline-none"
                            placeholder="••••••••" required>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700">تأكيد كلمة المرور</label>
                        <input type="password" name="password_confirmation"
                            class="w-full mt-2 px-4 py-3 border rounded-xl
                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                        transition outline-none"
                            placeholder="••••••••" required>
                    </div>

                    {{-- ACCOUNT TYPE (UX IMPROVED - CARD STYLE) --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700">نوع الحساب</label>

                        <div class="grid grid-cols-2 gap-3 mt-3">

                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="patient" class="peer hidden" checked>
                                <div
                                    class="border rounded-xl p-4 text-center transition
                                peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                    <div class="font-medium">مريض</div>
                                    <div class="text-xs text-gray-500 mt-1">حجز ومتابعة طبية</div>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="clinic" class="peer hidden">
                                <div
                                    class="border rounded-xl p-4 text-center transition
                                peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                    <div class="font-medium">عيادة</div>
                                    <div class="text-xs text-gray-500 mt-1">إدارة المرضى والمواعيد</div>
                                </div>
                            </label>

                        </div>

                        @error('type')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- SUBMIT --}}
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white
                    py-3 rounded-xl font-medium transition shadow-sm">
                        إنشاء الحساب
                    </button>

                    {{-- LOGIN --}}
                    <div class="text-center text-sm text-gray-600">
                        عندك حساب بالفعل؟
                        <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">
                            تسجيل الدخول
                        </a>
                    </div>

                </form>
            </div>
        </div>

    </div>
@endsection
