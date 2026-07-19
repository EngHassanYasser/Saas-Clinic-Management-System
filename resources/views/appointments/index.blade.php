@extends('layouts-main.dashboard')

@section('title', 'موعدي')

@section('content')
    <div class="flex min-h-screen" dir="rtl" x-data="bookingForm({
        appointments: @js($appointments->items()),
        stats: @js($stats)
    })">
        <main class="flex-1 overflow-x-hidden">

            <div class="p-4 sm:p-8 space-y-6">
                <x-appointments.status-row />

                <x-appointments.filter-tabs />
                <x-shared.errors/>
                <div x-show="appointments.length == 0"
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
                    <div class="w-20 h-20 bg-teal-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-times text-teal-400 text-3xl"></i>
                    </div>
                    <h3 class="text-gray-700 font-bold text-lg mb-2">لا توجد مواعيد</h3>
                    <p class="text-gray-400 text-sm mb-6">ابدأ بحجز أول موعد طبي لك</p>
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-6 py-2.5 rounded-xl font-semibold transition text-sm">
                        <i class="fas fa-search text-xs"></i>
                        ابحث عن عيادة
                    </a>
                </div>
                <div class="space-y-4">
                    <template x-for="appointment in appointments" :key="appointment.id">
                        <div>
                            <x-appointments.cart />
                        </div>
                    </template>
                </div>
            </div>
        </main>
        <x-appointments.cancel-confirm-model />
        <x-appointments.reschedule />
    </div>
@endsection
