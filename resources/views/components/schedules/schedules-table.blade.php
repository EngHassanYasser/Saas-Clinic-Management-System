 <div x-show="open" x-transition x-cloak class="border-t border-gray-100">

<template x-if="doctor.schedules.length === 0">
    <div class="py-8 text-center text-sm text-gray-400">
        <i class="fa fa-calendar-xmark text-2xl mb-2 block text-gray-200"></i>
        لا توجد مواعيد مضافة بعد
    </div>
</template>

<template x-if="doctor.schedules.length > 0">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 text-xs text-gray-500">
                <th class="text-right px-4 py-2.5 font-medium">الأيام</th>
                <th class="text-right px-4 py-2.5 font-medium">من</th>
                <th class="text-right px-4 py-2.5 font-medium">إلى</th>
                <th class="text-right px-4 py-2.5 font-medium">بريك</th>
                <th class="text-right px-4 py-2.5 font-medium">مدة الكشف</th>
                <th class="px-4 py-2.5"></th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-50">
            <template x-for="schedule in doctor.schedules" :key="schedule.id">
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1">

                            <template x-for="day in schedule.days" :key="day.id">
                                <span
                                    class="text-xs bg-teal-50 text-teal-700 border border-teal-100 px-2 py-0.5 rounded-md"
                                    x-text="day.name">
                                </span>
                            </template>

                        </div>
                    </td>

                    <td class="px-4 py-3" x-text="schedule.start_time"></td>

                    <td class="px-4 py-3" x-text="schedule.end_time"></td>

                    <td class="px-4 py-3 text-xs">
                        <template x-if="schedule.start_break && schedule.end_break">
                            <span x-text="`${schedule.start_break} — ${schedule.end_break}`">
                            </span>
                        </template>

                        <template x-if="!schedule.start_break || !schedule.end_break">
                            <span class="text-gray-300">—</span>
                        </template>
                    </td>

                    <td class="px-4 py-3">
                        <span x-text="schedule.slot_duration"></span> د
                    </td>

                    <td class="px-4 py-3">
                        <button @click.stop="openEdit(schedule)">
                            تعديل
                        </button>
                    </td>

                </tr>
            </template>
        </tbody>
    </table>
</template>
</div>