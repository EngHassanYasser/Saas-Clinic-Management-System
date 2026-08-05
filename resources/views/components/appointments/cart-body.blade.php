<div class="c-clinic-appointment-card bg-white border border-gray-100 rounded-2xl p-4 sm:p-5 space-y-5" dir="rtl">
    <div class="flex flex-col sm:flex-row sm:flex-wrap gap-y-4 gap-x-8">
        @if (Auth()->user()->type == \App\Enums\RoleType::CLINIC)
            <div class="flex items-center gap-3 sm:w-[calc(50%-1rem)] lg:w-auto lg:flex-1">
                <div
                    class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center border border-teal-100 flex-shrink-0">
                    <i class="fas fa-user text-teal-700"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-gray-400">المريض</p>
                    <p class="font-semibold text-gray-900 text-sm truncate" x-text="appointment.patient.name">
                    </p>
                </div>
            </div>
        @endif
        <div class="flex items-center gap-3 sm:w-[calc(50%-1rem)] lg:w-auto lg:flex-1">
            <div
                class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center border border-blue-100 flex-shrink-0">
                <i class="fas fa-user-md text-blue-700"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-gray-400">الطبيب</p>
                <p class="font-semibold text-gray-900 text-sm truncate" x-text="appointment.doctor.name"></p>
            </div>
        </div>
        @if (Auth()->user()->type == \App\Enums\RoleType::PATIENT)
            <div class="flex items-start gap-3 sm:w-[calc(50%-1rem)] lg:w-auto lg:flex-1">
                <div
                    class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center border border-amber-100 flex-shrink-0">
                    <i class="fas fa-hospital text-amber-700"></i>
                </div>

                <div class="min-w-0">
                    <p class="text-xs text-gray-400 mb-0.5">العيادة</p>
                    <p class="font-semibold text-gray-900 text-sm truncate" x-text="appointment.clinic.name"></p>
                    <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                        <i class="fas fa-map-marker-alt text-gray-400 flex-shrink-0"></i>
                        <span class="truncate" x-text="appointment.clinic.address"></span>
                    </p>
                </div>
            </div>
        @endif
        <div class="flex items-center gap-3 sm:w-[calc(50%-1rem)] lg:w-auto lg:flex-1">
            <div
                class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center border border-blue-100 flex-shrink-0">
                <i class="fas fa-calendar-day text-blue-700"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-gray-400">الموعد</p>
                <p dir="ltr" class="font-semibold text-gray-900 text-sm"
                    x-text="`${appointment.visit_date} ${convertUtcToLocalTime(appointment.visit_date, appointment.start_time)} - ${convertUtcToLocalTime(appointment.visit_date, appointment.end_time)}`">
                </p>
            </div>
        </div>
    </div>
    <div class="border-t border-gray-100"></div>
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div class="flex flex-wrap gap-2">
            <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2">
                <i class="fas fa-notes-medical text-gray-400 text-xs"></i>
                <div>
                    <p class="text-[11px] text-gray-400 leading-none">الخدمة</p>
                    <p class="text-xs font-semibold text-gray-700 mt-0.5" x-text="appointment.appointment_type"></p>
                </div>
            </div>
            <x-appointments.prices />
        </div>
        <x-appointments.actions />
    </div>
</div>
