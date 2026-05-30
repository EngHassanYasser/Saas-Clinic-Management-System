    <!-- Doctor -->
    <div class="flex items-start gap-3">

        <div class="w-11 h-11 rounded-xl bg-teal-50 flex items-center justify-center border border-teal-100">
            <i class="fas fa-user-md text-teal-600"></i>
        </div>

        <div>
            <p class="text-xs text-gray-400">الطبيب</p>
            <p class="font-bold text-gray-900 text-sm">
                {{ $appt['doctor_name'] }}
            </p>

            <span
                class="inline-block mt-1 text-xs bg-teal-50 text-teal-700 px-2 py-0.5 rounded-full border border-teal-100">
                {{ $appt['specialty'] }}
            </span>
        </div>

    </div>
