<div x-show="open" x-transition x-cloak class="border-t border-gray-200">
    <template x-if="doctor.schedules.length === 0">
        <div class="py-10 text-center">
            <i class="fa-regular fa-calendar-xmark text-4xl text-gray-300 mb-3"></i>
            <p class="text-sm text-gray-500">لا توجد مواعيد مضافة بعد</p>
        </div>
    </template>
    <template x-if="doctor.schedules.length > 0">
        <div class="overflow-hidden rounded-xl border border-gray-200 m-4">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr class="text-gray-600">
                        <th class="px-5 py-3 text-right font-semibold">الأيام</th>
                        <th class="px-5 py-3 text-right font-semibold">من</th>
                        <th class="px-5 py-3 text-right font-semibold">إلى</th>
                        <th class="px-5 py-3 text-right font-semibold">البريك</th>
                        <th class="px-5 py-3 text-right font-semibold">مدة الكشف</th>
                        <th class="px-5 py-3 text-center font-semibold">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <template x-for="schedule in doctor.schedules" :key="schedule.id">
                        <tr class="hover:bg-gray-50 transition-colors">
                            {{-- الأيام --}}
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-2">

                                    <template x-for="day in schedule.days" :key="day.id">
                                        <span
                                            class="inline-flex items-center rounded-md border border-gray-200 bg-white px-2.5 py-1 text-xs font-medium text-gray-700"
                                            x-text="day.name">
                                        </span>
                                    </template>

                                </div>
                            </td>
                            {{-- من --}}
                            <td class="px-5 py-4 font-medium text-gray-800"
                                x-text="formatTime12Hours(schedule.startTime)">
                            </td>
                            {{-- إلى --}}
                            <td class="px-5 py-4 font-medium text-gray-800"
                                x-text="formatTime12Hours(schedule.endTime)">
                            </td>
                            {{-- البريك --}}
                            <td class="px-5 py-4">
                                <template x-if="schedule.startBreak && schedule.endBreak">
                                    <span class="text-gray-600"
                                        x-text="`${formatTime12Hours(schedule.startBreak)} - ${formatTime12Hours(schedule.endBreak)}`">
                                    </span>
                                </template>
                                <template x-if="!schedule.startBreak || !schedule.endBreak">
                                    <span class="text-gray-300">—</span>
                                </template>
                            </td>
                            {{-- مدة الكشف --}}
                            <td class="px-5 py-4">
                                <span class="text-gray-800 font-medium" x-text="schedule.slotDuration"></span>

                                <span class="text-gray-400 text-xs">
                                    دقيقة
                                </span>
                            </td>
                            {{-- الإجراءات --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button @click.stop="openEdit(schedule,doctor)"
                                        class="inline-flex items-center gap-1 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-medium text-gray-700 transition hover:bg-gray-100">
                                        <i class="fa-regular fa-pen-to-square text-gray-500"></i>
                                        تعديل
                                    </button>
                                    <form :action="'/schedules/' + schedule.id" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" @click.stop
                                            class="inline-flex items-center gap-1 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-medium text-red-600 transition hover:bg-red-100">
                                            <i class="fa-regular fa-trash-can"></i>
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </template>
</div>
