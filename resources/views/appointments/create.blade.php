@extends('layouts-main.dashboard')

@section('content')
<div class="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8">

    <!-- hidden classes for tailwind -->
    <div class="hidden bg-green-600 border-green-600 text-white"></div>

    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
            حجز موعد جديد
        </h1>

        <p class="text-gray-500 text-sm mt-1">
            اختار الخدمة → الدكتور → اليوم → الموعد
        </p>
    </div>

    <form class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        @csrf

        <div class="p-5 sm:p-6 space-y-5">

            <!-- STEP CARD -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <!-- SERVICE -->
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <label class="text-xs font-semibold text-gray-600">
                        الخدمة
                    </label>

                    <select id="service"
                        class="mt-2 w-full rounded-lg border-gray-200 p-2.5 text-sm focus:ring-2 focus:ring-teal-100 outline-none">

                        <option value="">اختار الخدمة</option>
                        <option value="checkup">كشف</option>
                        <option value="followup">متابعة</option>
                        <option value="xray">أشعة</option>

                    </select>
                </div>

                <!-- DOCTOR -->
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">

                    <label class="text-xs font-semibold text-gray-600">
                        الدكتور
                    </label>

                    <select id="doctor"
                        class="mt-2 w-full rounded-lg border-gray-200 p-2.5 text-sm focus:ring-2 focus:ring-teal-100 outline-none">

                        <option value="">اختار الدكتور</option>
                        <option value="1">د. أحمد علي</option>
                        <option value="2">د. محمد حسن</option>

                    </select>

                </div>

            </div>

            <!-- DATE -->
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">

                <label class="text-xs font-semibold text-gray-600">
                    اليوم
                </label>

                <input type="date"
                    id="date"
                    class="mt-2 w-full rounded-lg border-gray-200 p-2.5 text-sm focus:ring-2 focus:ring-teal-100 outline-none">

            </div>

            <!-- SLOTS -->
            <div class="pt-2">

                <div class="text-center mb-3">

                    <label class="text-sm font-semibold text-gray-700 block">
                        المواعيد المتاحة
                    </label>

                    <span id="status"
                        class="text-xs text-gray-400">
                    </span>

                </div>

                <div id="slots"
                    class="flex flex-wrap justify-center gap-2">
                </div>

            </div>

            <input type="hidden"
                name="selected_time"
                id="selected_time">

        </div>

        <!-- FOOTER -->
        <div class="p-4 bg-gray-50 border-t flex justify-end">

            <button type="submit"
                class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-teal-600 text-white text-sm font-semibold  transition">

                تأكيد الحجز

            </button>

        </div>

    </form>
</div>

<script>
    const slotsContainer = document.getElementById('slots');
    const statusText = document.getElementById('status');
    const selectedTimeInput = document.getElementById('selected_time');

    function generateSlots(date) {

        if (!date) return [];

        const baseSlots = [
            "09:00",
            "09:30",
            "10:00",
            "10:30",
            "11:00",
            "11:30",
            "12:00",
            "12:30",
            "01:00",
            "01:30",
            "02:00"
        ];

        const seed = new Date(date).getDate();

        return baseSlots.filter((_, i) => (i + seed) % 3 !== 0);
    }

    function renderSlots(date) {

        slotsContainer.innerHTML = "";
        selectedTimeInput.value = "";

        const slots = generateSlots(date);

        if (!date) {
            statusText.innerText = "اختار يوم";
            return;
        }

        if (slots.length === 0) {
            statusText.innerText = "مفيش مواعيد";
            return;
        }

        statusText.innerText = "اختار ميعاد";

        slots.forEach(time => {

            const btn = document.createElement('button');

            btn.type = "button";
            btn.innerText = time;

            btn.className = `
                px-4 py-2 rounded-full text-sm border
                border-gray-200 bg-white text-gray-700
                 hover:border-teal-300
                transition-all duration-200
            `;

            btn.onclick = () => {

                document.querySelectorAll('#slots button')
                    .forEach(b => {

                        b.classList.remove(
                            'bg-green-600',
                            'text-white',
                            'border-green-600',
                            'scale-105'
                        );

                        b.classList.add(
                            'bg-white',
                            'text-gray-700',
                            'border-gray-200'
                        );
                    });

                btn.classList.remove(
                    'bg-white',
                    'text-gray-700',
                    'border-gray-200'
                );

                btn.classList.add(
                    'bg-green-600',
                    'text-white',
                    'border-green-600',
                    'scale-105'
                );

                selectedTimeInput.value = time;
            };

            slotsContainer.appendChild(btn);
        });
    }

    document.getElementById('date')
        .addEventListener('change', (e) => {

            renderSlots(e.target.value);

        });
</script>
@endsection