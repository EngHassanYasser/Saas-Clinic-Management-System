@extends('layouts-main.App')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-6 py-6 sm:py-10">

    <!-- HEADER -->
    <div class="flex flex-col text-center sm:flex-row sm:items-start sm:justify-between gap-4 mb-8 sm:mb-10">

        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">
                عيادة الشفاء
            </h1>
            <p class="text-gray-500 mt-1 sm:mt-2 text-sm">
                القاهرة - العباسية - شارع رمسيس
            </p>
        </div>

        <span class="self-start sm:self-auto text-center px-3 sm:px-4 py-1.5 rounded-full text-xs sm:text-sm bg-emerald-50 text-emerald-700 border border-emerald-100">
            متاح للحجز
        </span>

    </div>

    <!-- GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 sm:gap-6">

        <!-- LEFT -->
        <div class="lg:col-span-2 space-y-5 sm:space-y-6">

            <!-- ABOUT -->
            <div class="bg-white border border-gray-100 rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-3">
                    عن العيادة
                </h2>
                <p class="text-gray-600 text-sm leading-relaxed">
                    عيادة متخصصة في الباطنة والقلب وتقديم خدمات طبية متكاملة باستخدام أحدث الأجهزة الطبية مع فريق طبي ذو خبرة عالية.
                </p>
            </div>

            <!-- DOCTORS -->
              <!-- DOCTORS -->
            <div class="bg-white border border-gray-100 rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm">

                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">
                    الأطباء
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">

                    <!-- Doctor 1 -->
                    <div class="p-4 rounded-xl border hover:bg-gray-50 transition space-y-3">

                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-semibold text-gray-900 text-sm sm:text-base">د. أحمد محمود</p>
                                <p class="text-xs text-gray-500">باطنة وقلب</p>
                            </div>

                            <span class="text-xs px-2 sm:px-3 py-1 rounded-full bg-gray-100 text-gray-600">
                                12 سنة خبرة
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-xs bg-blue-50 border border-blue-100 rounded-lg px-3 py-2">
                            <span class="text-gray-600">أقرب موعد</span>
                            <span class="font-semibold text-blue-700">
                                غدًا 10:00 ص
                            </span>
                        </div>

                    </div>

                    <!-- Doctor 2 -->
                    <div class="p-4 rounded-xl border hover:bg-gray-50 transition space-y-3">

                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-semibold text-gray-900 text-sm sm:text-base">د. سارة علي</p>
                                <p class="text-xs text-gray-500">باطنة</p>
                            </div>

                            <span class="text-xs px-2 sm:px-3 py-1 rounded-full bg-gray-100 text-gray-600">
                                8 سنوات خبرة
                            </span>
                        </div>

                        <div class="flex items-center justify-between text-xs bg-blue-50 border border-blue-100 rounded-lg px-3 py-2">
                            <span class="text-gray-600">أقرب موعد</span>
                            <span class="font-semibold text-blue-700">
                                بعد بكرة 1:00 م
                            </span>
                        </div>

                    </div>

                </div>
            </div>


        </div>

        <!-- RIGHT -->
        <div class="space-y-5 sm:space-y-6 lg:sticky lg:top-6">

            <!-- PRICES -->
            <div class="bg-white border border-gray-100 rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm">

                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">
                    الأسعار
                </h2>

                <div class="space-y-3 text-sm">

                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">الكشف</span>
                        <span class="font-semibold text-gray-900">300 جنيه</span>
                    </div>

                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-600">رسم القلب</span>
                        <span class="font-semibold text-gray-900">200 جنيه</span>
                    </div>

                    <div class="flex justify-between py-2">
                        <span class="text-gray-600">متابعة الضغط</span>
                        <span class="font-semibold text-gray-900">150 جنيه</span>
                    </div>

                </div>
            </div>

            <!-- BOOKING -->
            <div class="bg-white border border-gray-100 rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm">

                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">
                    حجز موعد
                </h2>

                <div class="space-y-3">

                    <select class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        <option>اختر الدكتور</option>
                        <option>د. أحمد محمود</option>
                        <option>د. سارة علي</option>
                    </select>

                    <select class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                        <option>نوع الخدمة</option>
                        <option>كشف عام</option>
                        <option>رسم قلب</option>
                        <option>متابعة ضغط</option>
                    </select>

                    <input type="date"
                        class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                        min="{{ date('Y-m-d') }}">

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">

                        <span class="text-xs text-center px-3 py-2 rounded-xl bg-gray-100 text-gray-500 border">
                            اختر التاريخ أولاً
                        </span>

                    </div>

                    <button class="w-full mt-2 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold transition">
                        تأكيد الحجز
                    </button>
                   <span class="text-sm text-red-600 mt-1 block text-center font-medium">
                       يرجي دفع 20% من قيمة الكشف  لتأكيد حجزك
                    </span>

                </div>
            </div>
        </div>

    </div>

</div>
@endsection