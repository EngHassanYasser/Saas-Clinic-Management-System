@extends('layouts-main.dashboard')

@section('content')

<div dir="rtl" x-data="bookingApp" class="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8">

    {{-- ===== HEADER ===== --}}
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">حجز موعد جديد</h1>
        <p class="text-gray-500 text-sm mt-1">اختار الخدمة → الدكتور → اليوم → الموعد</p>
    </div>

    <form class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        @csrf

        <div class="p-5 sm:p-6 space-y-5">

            {{-- ===== SERVICE + DOCTOR ===== --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <label class="text-xs font-semibold text-gray-600">الخدمة</label>
                    <select x-model="service"
                        class="mt-2 w-full rounded-lg border border-gray-200 p-2.5 text-sm focus:ring-2 focus:ring-teal-100 focus:outline-none bg-white">
                        <option value="">اختار الخدمة</option>
                        <option value="checkup">كشف</option>
                        <option value="followup">متابعة</option>
                        <option value="xray">أشعة</option>
                    </select>
                </div>

                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <label class="text-xs font-semibold text-gray-600">الدكتور</label>
                    <select x-model="doctor"
                        class="mt-2 w-full rounded-lg border border-gray-200 p-2.5 text-sm focus:ring-2 focus:ring-teal-100 focus:outline-none bg-white">
                        <option value="">اختار الدكتور</option>
                        <option value="1">د. أحمد علي</option>
                        <option value="2">د. محمد حسن</option>
                    </select>
                </div>

            </div>

            {{-- ===== DATE ===== --}}
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                <label class="text-xs font-semibold text-gray-600">اليوم</label>
                <input type="date" x-model="date" @change="onDateChange()"
                    class="mt-2 w-full rounded-lg border border-gray-200 p-2.5 text-sm focus:ring-2 focus:ring-teal-100 focus:outline-none bg-white">
            </div>

            {{-- ===== SLOTS ===== --}}
            <div class="pt-2">

                <div class="text-center mb-3">
                    <label class="text-sm font-semibold text-gray-700 block">المواعيد المتاحة</label>
                    <span class="text-xs text-gray-400" x-text="statusText"></span>
                </div>

                <div class="flex flex-wrap justify-center gap-2">
                    <template x-for="slot in slots" :key="slot">
                        <button type="button" @click="selectSlot(slot)" x-text="slot"
                            class="px-4 py-2 rounded-full text-sm border transition-all duration-200"
                            :class="selectedTime === slot
                                ? 'bg-teal-600 text-white border-teal-600 scale-105 shadow-sm'
                                : 'bg-white text-gray-700 border-gray-200 hover:border-teal-300'">
                        </button>
                    </template>
                </div>

            </div>

            <input type="hidden" name="selected_time" :value="selectedTime">

        </div>

        {{-- ===== FOOTER ===== --}}
        <div class="p-4 bg-gray-50 border-t flex justify-end">
            <button type="submit"
                :disabled="!selectedTime"
                class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-teal-600 text-white text-sm font-semibold transition disabled:opacity-40 disabled:cursor-not-allowed">
                تأكيد الحجز
            </button>
        </div>
    </form>

</div>
@endsection