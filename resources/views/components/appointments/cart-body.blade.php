<div class="c-appointment-card bg-white border border-gray-100 rounded-2xl overflow-hidden">
    <div class="p-5 space-y-5">

        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

            <x-appointments.doctor-info :$appt />
            @if (auth()->user()->type == 'patient')
                <x-appointments.clinic-info :$appt />
            @endif
            <x-appointments.date $:appt />

        </div>

        <!-- DIVIDER -->
        <div class="border-t border-gray-100"></div>

        <x-appointments.prices :$appt />

        <x-appointments.actions :$appt />

    </div>
</div>
