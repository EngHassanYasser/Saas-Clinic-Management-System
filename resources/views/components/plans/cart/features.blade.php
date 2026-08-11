<div class="space-y-4 border-t border-gray-100 p-6">

    <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-500">
        حدود الباقة
    </h3>

    <div class="space-y-3">

        <div
            class="flex items-center justify-between rounded-xl bg-gray-50 px-4 py-3 transition hover:bg-indigo-50">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100">

                    <i class="fa-solid fa-user-doctor text-indigo-600"></i>

                </div>

                <div>

                    <p class="text-sm font-medium text-gray-800">
                        الحد الأقصى للأطباء
                    </p>

                    <p class="text-xs text-gray-500">
                        عدد الأطباء المسموح بهم
                    </p>

                </div>

            </div>

            <span
                class="rounded-lg bg-white px-3 py-1 text-lg font-bold text-gray-900 shadow-sm"
                x-text="plan.maxDoctors">
            </span>

        </div>

        <div
            class="flex items-center justify-between rounded-xl bg-gray-50 px-4 py-3 transition hover:bg-indigo-50">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100">

                    <i class="fa-solid fa-calendar-check text-green-600"></i>

                </div>

                <div>

                    <p class="text-sm font-medium text-gray-800">
                        الحد الأقصى للحجوزات
                    </p>

                    <p class="text-xs text-gray-500">
                        عدد الحجوزات الشهرية
                    </p>

                </div>

            </div>

            <span
                class="rounded-lg bg-white px-3 py-1 text-lg font-bold text-gray-900 shadow-sm"
                x-text="plan.monthlyAppointmentsLimit">
            </span>

        </div>

    </div>

</div>