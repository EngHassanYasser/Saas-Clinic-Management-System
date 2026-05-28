@extends('layouts-main.App')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            نتائج البحث عن العيادات
        </h1>
        <p class="text-gray-500 mt-1">
            تم العثور على نتائج مناسبة بناءً على بحثك
        </p>
    </div>

    <!-- List -->
    <div class="space-y-5">

        <!-- Card -->
        <div class="bg-white border border-gray-100 rounded-2xl p-5 flex gap-5 shadow-sm hover:shadow-lg transition-all duration-300">

            <!-- Image -->
            <div class="w-28 h-28 flex-shrink-0">
                <img src="https://via.placeholder.com/150"
                     class="w-full h-full object-cover rounded-2xl"
                     alt="clinic">
            </div>

            <!-- Content -->
            <div class="flex-1">

                <!-- Name + badge -->
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">
                        عيادة الشفاء
                    </h2>

                    <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full">
                        متاح للحجز
                    </span>
                </div>

                <!-- meta -->
                <div class="mt-3 space-y-1 text-sm text-gray-600">

                    <p>
                        <span class="font-semibold text-gray-800">التخصصات:</span>
                        باطنة، قلب
                    </p>

                    <p>
                        <span class="font-semibold text-gray-800">الخدمات:</span>
                        كشف عام، رسم قلب
                    </p>

                    <p>
                        <span class="font-semibold text-gray-800">العنوان:</span>
                        القاهرة - العباسية
                    </p>

                </div>

                <!-- appointment -->
                <div class="mt-4">
                    <span class="text-sm font-semibold text-gray-700">
                        أقرب موعد:
                    </span>

                    <span class="ml-1 text-sm text-green-600 font-bold">
                        2026-05-30 10:00
                    </span>
                </div>

            </div>

            <!-- Action -->
            <div class="flex items-center">
                <a href="#"
                   class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-5 py-2.5 rounded-xl transition">
                    عرض التفاصيل
                </a>
            </div>

        </div>

        <!-- Card 2 -->
        <div class="bg-white border border-gray-100 rounded-2xl p-5 flex gap-5 shadow-sm hover:shadow-lg transition-all duration-300">

            <div class="w-28 h-28 flex-shrink-0">
                <img src="https://via.placeholder.com/150"
                     class="w-full h-full object-cover rounded-2xl">
            </div>

            <div class="flex-1">

                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">
                        مركز الحياة الطبي
                    </h2>

                    <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                        جلدية
                    </span>
                </div>

                <div class="mt-3 space-y-1 text-sm text-gray-600">

                    <p>
                        <span class="font-semibold text-gray-800">التخصصات:</span>
                        جلدية
                    </p>

                    <p>
                        <span class="font-semibold text-gray-800">الخدمات:</span>
                        ليزر، علاج حب الشباب
                    </p>

                    <p>
                        <span class="font-semibold text-gray-800">العنوان:</span>
                        الجيزة - الدقي
                    </p>

                </div>

                <div class="mt-4">
                    <span class="text-sm font-semibold text-gray-700">
                        أقرب موعد:
                    </span>

                    <span class="ml-1 text-sm text-green-600 font-bold">
                        2026-05-29 14:30
                    </span>
                </div>

            </div>

            <div class="flex items-center">
                <a href="#"
                   class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-5 py-2.5 rounded-xl transition">
                    عرض التفاصيل
                </a>
            </div>

        </div>

        <!-- Card 3 -->
        <div class="bg-white border border-gray-100 rounded-2xl p-5 flex gap-5 shadow-sm hover:shadow-lg transition-all duration-300">

            <div class="w-28 h-28 flex-shrink-0">
                <img src="https://via.placeholder.com/150"
                     class="w-full h-full object-cover rounded-2xl">
            </div>

            <div class="flex-1">

                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">
                        عيادة النور
                    </h2>

                    <span class="text-xs bg-red-100 text-red-600 px-3 py-1 rounded-full">
                        غير متاح
                    </span>
                </div>

                <div class="mt-3 space-y-1 text-sm text-gray-600">

                    <p>
                        <span class="font-semibold text-gray-800">التخصصات:</span>
                        أسنان
                    </p>

                    <p>
                        <span class="font-semibold text-gray-800">الخدمات:</span>
                        حشو، تنظيف
                    </p>

                    <p>
                        <span class="font-semibold text-gray-800">العنوان:</span>
                        مدينة نصر
                    </p>

                </div>

                <div class="mt-4">
                    <span class="text-sm font-semibold text-gray-700">
                        أقرب موعد:
                    </span>

                    <span class="ml-1 text-sm text-red-500 font-bold">
                        غير متاح حالياً
                    </span>
                </div>

            </div>

            <div class="flex items-center">
                <a href="#"
                   class="bg-gray-900 hover:bg-black text-white text-sm px-5 py-2.5 rounded-xl transition">
                    عرض التفاصيل
                </a>
            </div>

        </div>

    </div>

</div>
@endsection