@extends('layouts-main.dashboard')

@section('title', 'إضافة طبيب جديد')

@section('content')

<div class="p-6 min-h-screen bg-gray-50" dir="rtl">

    {{-- ===================== HEADER ===================== --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-400 mb-2">
            <a href="" class="hover:text-teal-600">الرئيسية</a>
            <i class="fa fa-chevron-left text-xs"></i>
            <a href="" class="hover:text-teal-600">الأطباء</a>
            <i class="fa fa-chevron-left text-xs"></i>
            <span class="text-gray-600">إضافة طبيب</span>
        </div>
        <h1 class="text-xl font-medium text-gray-800">إضافة طبيب جديد</h1>
    </div>

    <form action="" method="POST" enctype="multipart/form-data" id="doctorForm">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- ===================== RIGHT COL ===================== --}}
            <div class="xl:col-span-2 flex flex-col gap-6">

                {{-- البيانات الأساسية --}}
                <div class="bg-white rounded-xl border border-gray-100 p-5">
                    <h2 class="text-sm font-medium text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fa fa-user text-teal-500"></i> البيانات الأساسية
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-xs text-gray-500 mb-1.5">اسم الطبيب <span class="text-red-400">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition"
                                placeholder="د. محمد أحمد">
                            @error('name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1.5">التخصص <span class="text-red-400">*</span></label>
                            <select name="specialty"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition bg-white">
                                <option value="">اختر التخصص</option>
                                <option value="general" {{ old('specialty') == 'general' ? 'selected' : '' }}>طب عام</option>
                                <option value="cardiology" {{ old('specialty') == 'cardiology' ? 'selected' : '' }}>قلب وأوعية دموية</option>
                                <option value="orthopedics" {{ old('specialty') == 'orthopedics' ? 'selected' : '' }}>عظام</option>
                                <option value="dermatology" {{ old('specialty') == 'dermatology' ? 'selected' : '' }}>جلدية</option>
                                <option value="pediatrics" {{ old('specialty') == 'pediatrics' ? 'selected' : '' }}>أطفال</option>
                                <option value="neurology" {{ old('specialty') == 'neurology' ? 'selected' : '' }}>مخ وأعصاب</option>
                                <option value="gynecology" {{ old('specialty') == 'gynecology' ? 'selected' : '' }}>نساء وتوليد</option>
                                <option value="ophthalmology" {{ old('specialty') == 'ophthalmology' ? 'selected' : '' }}>عيون</option>
                                <option value="ent" {{ old('specialty') == 'ent' ? 'selected' : '' }}>أنف وأذن وحنجرة</option>
                                <option value="other" {{ old('specialty') == 'other' ? 'selected' : '' }}>أخرى</option>
                            </select>
                            @error('specialty') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1.5">سعر الكشف (جنيه) <span class="text-red-400">*</span></label>
                            <div class="relative">
                                <input type="number" name="price" value="{{ old('price') }}" min="0"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition"
                                    placeholder="200">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">ج.م</span>
                            </div>
                            @error('price') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1.5">مدة الكشف (دقيقة) <span class="text-red-400">*</span></label>
                            <select name="session_duration"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition bg-white">
                                <option value="">اختر المدة</option>
                                <option value="15" {{ old('session_duration') == '15' ? 'selected' : '' }}>15 دقيقة</option>
                                <option value="20" {{ old('session_duration') == '20' ? 'selected' : '' }}>20 دقيقة</option>
                                <option value="30" {{ old('session_duration') == '30' ? 'selected' : '' }}>30 دقيقة</option>
                                <option value="45" {{ old('session_duration') == '45' ? 'selected' : '' }}>45 دقيقة</option>
                                <option value="60" {{ old('session_duration') == '60' ? 'selected' : '' }}>60 دقيقة</option>
                            </select>
                            @error('session_duration') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>

                    </div>
                </div>

                {{-- أيام العمل --}}
                <div class="bg-white rounded-xl border border-gray-100 p-5">
                    <h2 class="text-sm font-medium text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fa fa-calendar text-teal-500"></i> أيام العمل
                    </h2>
                    <div class="flex flex-wrap gap-2" id="daysContainer">
                        @php
                        $days = [
                            'saturday'  => 'السبت',
                            'sunday'    => 'الأحد',
                            'monday'    => 'الاثنين',
                            'tuesday'   => 'الثلاثاء',
                            'wednesday' => 'الأربعاء',
                            'thursday'  => 'الخميس',
                            'friday'    => 'الجمعة',
                        ];
                        @endphp
                        @foreach($days as $val => $label)
                        <label class="day-btn cursor-pointer">
                            <input type="checkbox" name="work_days[]" value="{{ $val }}" class="hidden day-check"
                                {{ in_array($val, old('work_days', [])) ? 'checked' : '' }}>
                            <span class="inline-block px-4 py-2 rounded-lg text-sm border border-gray-200 text-gray-600 transition select-none day-label">
                                {{ $label }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                    @error('work_days') <p class="text-xs text-red-400 mt-2">{{ $message }}</p> @enderror
                </div>

                {{-- المواعيد والبريك --}}
                <div class="bg-white rounded-xl border border-gray-100 p-5">
                    <h2 class="text-sm font-medium text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fa fa-clock text-teal-500"></i> أوقات العمل والبريك
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-xs text-gray-500 mb-1.5">بداية الدوام <span class="text-red-400">*</span></label>
                            <input type="time" name="work_start" value="{{ old('work_start') }}"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
                            @error('work_start') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1.5">نهاية الدوام <span class="text-red-400">*</span></label>
                            <input type="time" name="work_end" value="{{ old('work_end') }}"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
                            @error('work_end') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1.5">بداية البريك</label>
                            <input type="time" name="break_start" value="{{ old('break_start') }}"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1.5">نهاية البريك</label>
                            <input type="time" name="break_end" value="{{ old('break_end') }}"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">
                        </div>

                    </div>

                    {{-- Preview المواعيد --}}
                    <div class="mt-4 p-4 bg-teal-50 rounded-lg hidden" id="schedulePreview">
                        <p class="text-xs font-medium text-teal-700 mb-2"><i class="fa fa-eye ml-1"></i> معاينة المواعيد</p>
                        <div id="slotsContainer" class="flex flex-wrap gap-2"></div>
                    </div>

                </div>

            </div>

            {{-- ===================== LEFT COL ===================== --}}
            <div class="flex flex-col gap-6">

                {{-- رفع الصورة --}}
                <div class="bg-white rounded-xl border border-gray-100 p-5">
                    <h2 class="text-sm font-medium text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fa fa-image text-teal-500"></i> صورة الطبيب
                    </h2>
                    <div class="flex flex-col items-center">
                        <div id="imagePreview"
                            class="w-28 h-28 rounded-full border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-300 mb-4 overflow-hidden cursor-pointer hover:border-teal-300 transition"
                            onclick="document.getElementById('imageInput').click()">
                            <i class="fa fa-camera text-2xl mb-1"></i>
                            <span class="text-xs">رفع صورة</span>
                        </div>
                        <input type="file" name="image" id="imageInput" accept="image/*" class="hidden">
                        <p class="text-xs text-gray-400 text-center">JPG, PNG — حجم أقصى 2MB</p>
                        @error('image') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- ملاحظات --}}
                <div class="bg-white rounded-xl border border-gray-100 p-5">
                    <h2 class="text-sm font-medium text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fa fa-note-sticky text-teal-500"></i> ملاحظات
                    </h2>
                    <textarea name="notes" rows="4"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition resize-none"
                        placeholder="أي معلومات إضافية عن الطبيب...">{{ old('notes') }}</textarea>
                </div>

                {{-- الحالة --}}
                <div class="bg-white rounded-xl border border-gray-100 p-5">
                    <h2 class="text-sm font-medium text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fa fa-toggle-on text-teal-500"></i> الحالة
                    </h2>
                    <label class="flex items-center justify-between cursor-pointer">
                        <span class="text-sm text-gray-600">الطبيب متاح للحجز</span>
                        <div class="relative">
                            <input type="checkbox" name="is_active" id="toggleActive" class="sr-only" checked>
                            <div id="toggleBg" class="w-11 h-6 bg-teal-500 rounded-full transition-colors duration-200"></div>
                            <div id="toggleDot" class="absolute top-0.5 right-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200"></div>
                        </div>
                    </label>
                </div>

                {{-- Buttons --}}
                <div class="flex flex-col gap-3">
                    <button type="submit"
                        class="w-full bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium py-2.5 rounded-lg transition flex items-center justify-center gap-2">
                        <i class="fa fa-plus"></i> إضافة الطبيب
                    </button>
                    <a href=""
                        class="w-full text-center border border-gray-200 text-gray-600 hover:bg-gray-50 text-sm py-2.5 rounded-lg transition">
                        إلغاء
                    </a>
                </div>

            </div>
        </div>
    </form>
</div>

{{-- ===================== JS ===================== --}}
<script>
    // ===== أيام العمل =====
    document.querySelectorAll('.day-btn').forEach(btn => {
        const check = btn.querySelector('.day-check');
        const label = btn.querySelector('.day-label');

        const update = () => {
            if (check.checked) {
                label.classList.add('bg-teal-500', 'text-white', 'border-teal-500');
                label.classList.remove('text-gray-600', 'border-gray-200');
            } else {
                label.classList.remove('bg-teal-500', 'text-white', 'border-teal-500');
                label.classList.add('text-gray-600', 'border-gray-200');
            }
        };

        update();
        btn.addEventListener('click', () => { check.checked = !check.checked; update(); generateSlots(); });
    });

    // ===== رفع الصورة =====
    document.getElementById('imageInput').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
        };
        reader.readAsDataURL(file);
    });

    // ===== Toggle =====
    const toggleInput = document.getElementById('toggleActive');
    const toggleBg    = document.getElementById('toggleBg');
    const toggleDot   = document.getElementById('toggleDot');

    toggleInput.addEventListener('change', () => {
        if (toggleInput.checked) {
            toggleBg.classList.add('bg-teal-500');
            toggleBg.classList.remove('bg-gray-300');
            toggleDot.style.transform = 'translateX(0)';
        } else {
            toggleBg.classList.remove('bg-teal-500');
            toggleBg.classList.add('bg-gray-300');
            toggleDot.style.transform = 'translateX(20px)';
        }
    });

    // ===== معاينة المواعيد =====
    const timeInputs = ['work_start', 'work_end', 'break_start', 'break_end'];
    timeInputs.forEach(name => {
        document.querySelector(`[name="${name}"]`).addEventListener('change', generateSlots);
    });

    function generateSlots() {
        const start    = document.querySelector('[name="work_start"]').value;
        const end      = document.querySelector('[name="work_end"]').value;
        const bStart   = document.querySelector('[name="break_start"]').value;
        const bEnd     = document.querySelector('[name="break_end"]').value;
        const duration = parseInt(document.querySelector('[name="session_duration"]').value) || 30;

        if (!start || !end) return;

        const toMins = t => { const [h, m] = t.split(':').map(Number); return h * 60 + m; };
        const toTime = m => { const h = Math.floor(m / 60); const mn = m % 60; return `${String(h).padStart(2,'0')}:${String(mn).padStart(2,'0')}`; };

        let slots = [];
        let cur = toMins(start);
        const endMins = toMins(end);
        const bs = bStart ? toMins(bStart) : null;
        const be = bEnd   ? toMins(bEnd)   : null;

        while (cur + duration <= endMins) {
            if (bs && be && cur >= bs && cur < be) { cur = be; continue; }
            slots.push(toTime(cur));
            cur += duration;
        }

        const container = document.getElementById('slotsContainer');
        const preview   = document.getElementById('schedulePreview');

        if (slots.length === 0) { preview.classList.add('hidden'); return; }

        preview.classList.remove('hidden');
        container.innerHTML = slots.map(s =>
            `<span class="text-xs bg-white text-teal-700 border border-teal-200 px-2.5 py-1 rounded-lg">${s}</span>`
        ).join('');
    }

    document.querySelector('[name="session_duration"]').addEventListener('change', generateSlots);
</script>

@endsection