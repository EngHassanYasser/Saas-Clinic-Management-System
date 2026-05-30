@extends('layouts-main.dashboard')

@section('title', 'موعدي')

@section('content')
    <div class="flex min-h-screen" dir="rtl">
        {{-- ===================== MAIN ===================== --}}
        <main class="flex-1 overflow-x-hidden">

            <div class="p-4 sm:p-8 space-y-6">
                <x-appointments.status-row />

                <x-appointments.filter-tabs />

                {{-- ===== APPOINTMENTS LIST ===== --}}
                @php
                    // Demo data — replace with $appointments from controller
                    $demoAppointments = [
                        [
                            'id' => 1,
                            'doctor_name' => 'د. أحمد محمود',
                            'specialty' => 'باطنة وقلب',
                            'clinic_name' => 'عيادة الشفاء',
                            'address' => 'القاهرة - العباسية - شارع رمسيس',
                            'date' => 'الأحد، 1 يونيو 2025',
                            'time' => '10:00 ص',
                            'status' => 'confirmed',
                            'status_label' => 'مؤكد',
                            'service' => 'كشف عام',
                            'exam_price' => 300,
                            'deposit' => 60,
                            'booking_src' => 'mobileApp',
                            'can_cancel' => true,
                        ],
                    ];
                    $appointments = $appointments ?? $demoAppointments;
                @endphp

                @if (count($appointments) === 0)
                    {{-- Empty state --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
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
                @else
                    <div class="space-y-4">
                        @foreach ($appointments as $appt)
                            @php
                                $badgeClass = match ($appt['status']) {
                                    'confirmed' => 'badge-confirmed',
                                    'pending' => 'badge-pending',
                                    'cancelled' => 'badge-cancelled',
                                    'completed' => 'badge-completed',
                                    'noShow' => 'badge-noshow',
                                    default => 'badge-pending',
                                };
                                $statusIcon = match ($appt['status']) {
                                    'confirmed' => 'fa-check-circle',
                                    'pending' => 'fa-hourglass-half',
                                    'cancelled' => 'fa-times-circle',
                                    'completed' => 'fa-check-double',
                                    'noShow' => 'fa-user-times',
                                    default => 'fa-circle',
                                };
                            @endphp
                            <x-appointments.cart :$appt :$badgeClass :$statusIcon />
                        @endforeach
                    </div>

                @endif
            </div>
        </main>
    </div>
    <x-appointments.cancel-confirm-model />
@endsection
