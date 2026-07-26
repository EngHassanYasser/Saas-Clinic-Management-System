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