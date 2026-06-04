@extends('layouts-main.dashboard')

@section('content')

<div class="min-h-screen bg-gray-50 p-4 sm:p-6" dir="rtl">

    <!-- Form Container -->
    <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <!-- Top accent bar -->
        <div class="h-1 bg-blue-600"></div>

        <form id="complaintForm" method="POST" action="" class="p-6 sm:p-8 space-y-8">
            @csrf

            <!-- GRID SECTION -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Patient Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">اسم المريض</label>
                    <input type="text" name="patient_name"
                        class="w-full rounded-lg border border-gray-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                        placeholder="اسم المريض (اختياري)">
                </div>

                <!-- Department -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">القسم</label>
                    <select name="department"
                        class="w-full rounded-lg border border-gray-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        <option value="">اختر القسم</option>
                        <option value="reception">الاستقبال</option>
                        <option value="clinic">العيادات</option>
                        <option value="lab">التحاليل</option>
                        <option value="radiology">الأشعة</option>
                    </select>
                </div>

                <!-- Doctor -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الدكتور</label>
                    <input type="text" name="doctor_name"
                        class="w-full rounded-lg border border-gray-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                        placeholder="اسم الدكتور (اختياري)">
                </div>

                <!-- Visit Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ الزيارة</label>
                    <input type="date" name="visit_date"
                        class="w-full rounded-lg border border-gray-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نوع المشكلة</label>
                    <select name="type"
                        class="w-full rounded-lg border border-gray-200 px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        <option value="">اختر النوع</option>
                        <option value="medical">طبية</option>
                        <option value="service">خدمة</option>
                        <option value="waiting_time">وقت انتظار</option>
                        <option value="staff_behavior">تعامل الموظفين</option>
                        <option value="billing">فواتير</option>
                        <option value="other">أخرى</option>
                    </select>
                </div>

                <!-- Priority (highlighted card style) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">درجة الخطورة</label>
                    <select name="priority"
                        class="w-full rounded-lg border border-red-100 bg-red-50 px-4 py-2.5 focus:ring-2 focus:ring-red-400 outline-none transition">
                        <option value="low">منخفضة</option>
                        <option value="medium">متوسطة</option>
                        <option value="high">عالية</option>
                        <option value="urgent">حرجة</option>
                    </select>
                </div>

            </div>

            <!-- Description FULL WIDTH -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">تفاصيل الشكوى</label>
                <textarea name="description" rows="5"
                    class="w-full rounded-lg border border-gray-200 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition resize-none"
                    placeholder="اكتب تفاصيل المشكلة بشكل واضح ودقيق..."></textarea>
            </div>

            <!-- ACTIONS -->
            <div class="flex flex-col sm:flex-row gap-3 sm:justify-end pt-2">

                <button type="reset"
                    class="px-6 py-2.5 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition">
                    مسح
                </button>

                <button type="submit"
                    class="px-6 py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition shadow-sm">
                    إرسال الشكوى
                </button>

            </div>

        </form>
    </div>
</div>

@endsection