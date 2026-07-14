    <tbody class="divide-y divide-gray-50">
        <template x-if="vications.length === 0">
            <tr>
                <td colspan="7">

                    <div class="flex flex-col items-center justify-center py-16 text-gray-300">

                        <i class="fa fa-umbrella-beach text-5xl mb-4"></i>

                        <p class="text-sm">
                            لا توجد إجازات مطابقة
                        </p>

                    </div>

                </td>
            </tr>
        </template>

        <template x-for="v in vications" :key="v.id">

            <tr class="hover:bg-gray-50 transition">

                {{-- الطبيب --}}
                <td class="px-4 py-3">

                    <div class="flex items-center gap-2.5">

                        <div class="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center text-xs font-medium flex-shrink-0"
                            x-text="v.doctor.name.charAt(0)">
                        </div>

                        <span class="font-medium text-gray-700" x-text="v.doctor.name">
                        </span>

                    </div>

                </td>

                {{-- السبب --}}
                <td class="px-4 py-3 text-gray-500" x-text="v.reason">
                </td>

                {{-- من --}}
                <td class="px-4 py-3 text-gray-600" x-text="formatDate(v.start_date)">
                </td>

                {{-- إلى --}}
                <td class="px-4 py-3 text-gray-600" x-text="formatDate(v.end_date)">
                </td>

                {{-- المدة --}}
                <td class="px-4 py-3">

                    <span class="text-xs text-gray-500" x-text="daysBetween(v.start_date,v.end_date) + ' يوم'">
                    </span>

                </td>

                {{-- الحالة --}}
                <td class="px-4 py-3">

                    <span class="text-xs px-2.5 py-1 rounded-full" :class="statusClass(v.status)"
                        x-text="statusLabel(v.status)">
                    </span>

                </td>

                {{-- العمليات --}}
                <td class="px-4 py-3">

                    <div class="flex items-center gap-3">

                        <button @click="editVacation(v)" class="text-teal-500 hover:text-teal-700 transition">

                            <i class="fa fa-pen text-xs"></i>

                        </button>

                        <button @click="confirmDelete(v)" class="text-red-400 hover:text-red-600 transition">

                            <i class="fa fa-trash text-xs"></i>

                        </button>

                    </div>

                </td>

            </tr>

        </template>

    </tbody>
