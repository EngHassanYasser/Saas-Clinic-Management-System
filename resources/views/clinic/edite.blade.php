@extends('layouts-main.dashboard')

@section('content')
    <div class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8 text-center">
        <!-- HEADER -->
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 gap-2 mb-2">
                <i class="fa-solid fa-hospital text-teal-600"></i>
                إعدادات العيادة
            </h1>
            <p class="text-gray-500 mt-1">
                تعديل بيانات العيادة الأساسية
            </p>
        </div>

        <form action="" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

            @csrf
            @method('PUT')

            <!-- TOP -->
            <div class="p-6 sm:p-8 border-b border-gray-100">

                <div class="flex flex-col lg:flex-row gap-10">

                    <!-- LOGO -->
                    <div class="flex flex-col items-center lg:items-start gap-3">
                        <img id="preview" src="{{ $clinic->logo ?? 'https://via.placeholder.com/120' }}"
                            class="w-28 h-28 rounded-2xl object-cover border shadow-sm">

                        <label class="text-sm text-gray-600 flex items-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-upload text-teal-600"></i>
                            تغيير الصورة
                            <input type="file" name="logo" accept="image/*" onchange="previewImage(event)"
                                class="hidden">
                        </label>
                    </div>

                    <!-- INFO -->
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- NAME -->
                        <div>
                            <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                <i class="fa-solid fa-signature text-teal-600"></i>
                                اسم العيادة
                            </label>
                            <input type="text" name="name" value="{{ old('name', $clinic->name ?? '') }}"
                                placeholder="اسم العيادة"
                                class="mt-2 w-full rounded-xl border border-gray-200 p-3
                                      focus:ring-2 focus:ring-teal-100 focus:border-teal-500 outline-none">
                        </div>

                        <!-- PHONE -->
                        <div>
                            <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                <i class="fa-solid fa-phone text-teal-600"></i>
                                رقم الهاتف
                            </label>
                            <input type="text" name="phone" value="{{ old('phone', $clinic->phone ?? '') }}"
                                placeholder="01xxxxxxxxx"
                                class="mt-2 w-full rounded-xl border border-gray-200 p-3
                                      focus:ring-2 focus:ring-teal-100 focus:border-teal-500 outline-none">
                        </div>

                        <!-- EMAIL -->
                        <div>
                            <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                <i class="fa-solid fa-envelope text-teal-600"></i>
                                البريد الإلكتروني
                            </label>
                            <input type="email" name="email" value="{{ old('email', $clinic->email ?? '') }}"
                                placeholder="clinic@example.com"
                                class="mt-2 w-full rounded-xl border border-gray-200 p-3
                                      focus:ring-2 focus:ring-teal-100 focus:border-teal-500 outline-none">
                        </div>

                        <!-- ADDRESS -->
                        <div>
                            <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                <i class="fa-solid fa-location-dot text-teal-600"></i>
                                العنوان
                            </label>
                            <input type="text" name="address" value="{{ old('address', $clinic->address ?? '') }}"
                                placeholder="العنوان بالكامل"
                                class="mt-2 w-full rounded-xl border border-gray-200 p-3
                                      focus:ring-2 focus:ring-teal-100 focus:border-teal-500 outline-none">
                        </div>

                    </div>
                </div>
            </div>

            <!-- WORKING HOURS -->
            <div class="p-6 sm:p-8 border-b border-gray-100">
                <div class="max-w-3xl mx-auto">
                    <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-clock text-teal-600"></i>
                        مواعيد العمل الأسبوعية
                    </h2>

                    <!-- DAYS -->
                    <div class="mb-6">

                        <p class="text-center text-sm font-semibold text-gray-700 mb-3">
                            أيام العمل
                        </p>

                        @php
                            $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                            $selected = $clinic->work_days ?? ['Sunday', 'Monday', 'Tuesday', 'Wednesday'];
                        @endphp

                        <div class="flex flex-wrap justify-center gap-4">

                            @foreach ($days as $day)
                                <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">

                                    <input type="checkbox" name="work_days[]" value="{{ $day }}"
                                        {{ in_array($day, $selected) ? 'checked' : '' }} class="accent-teal-600">

                                    <span>{{ $day }}</span>

                                </label>
                            @endforeach

                        </div>

                    </div>
                    <!-- TIME RANGE -->
                    <div class="max-w-3xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- START -->
                        <div>
                            <label class="block text-center text-sm font-semibold text-gray-700 mb-1">
                                <i class="fa-solid fa-door-open text-teal-600"></i>
                                بداية العمل
                            </label>

                            <p class="text-xs text-gray-400 text-center mb-2">
                                وقت بدء استقبال المرضى
                            </p>

                            <input type="time" name="open_time" value="{{ old('open_time', $clinic->open_time ?? '') }}"
                                class="w-full rounded-xl border border-gray-200 p-3
                      focus:ring-2 focus:ring-teal-100 focus:border-teal-500 outline-none">
                        </div>

                        <!-- END -->
                        <div>
                            <label class="block text-center text-sm font-semibold text-gray-700 mb-1">
                                <i class="fa-solid fa-door-closed text-teal-600"></i>
                                نهاية العمل
                            </label>

                            <p class="text-xs text-gray-400 text-center mb-2">
                                وقت إغلاق العيادة
                            </p>

                            <input type="time" name="close_time"
                                value="{{ old('close_time', $clinic->close_time ?? '') }}"
                                class="w-full rounded-xl border border-gray-200 p-3
                      focus:ring-2 focus:ring-teal-100 focus:border-teal-500 outline-none">
                        </div>
                    </div>

                </div>
            </div>

            <!-- ACTIONS -->
            <div class="p-6 sm:p-8 flex flex-col sm:flex-row gap-3 sm:justify-center bg-gray-50">

                <button type="reset"
                    class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-white transition flex items-center gap-2">
                    <i class="fa-solid fa-rotate-left"></i>
                    مسح
                </button>

                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-teal-600 text-white hover:bg-teal-700 transition shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    حفظ التعديلات
                </button>

            </div>

        </form>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('preview').src = reader.result;
            };
            reader.readAsDataURL(file);
        }
    </script>
@endsection
