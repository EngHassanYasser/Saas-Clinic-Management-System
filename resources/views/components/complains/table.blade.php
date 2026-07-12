<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">

    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100 text-xs text-gray-400 bg-gray-50">
                <th class="text-right px-4 py-3 font-medium">المريض</th>
                <th class="text-right px-4 py-3 font-medium">الموضوع</th>
                <th class="text-right px-4 py-3 font-medium">ضد</th>
                <th class="text-right px-4 py-3 font-medium">ضد</th>
                <th class="text-right px-4 py-3 font-medium">الموضوع</th>
                <th class="text-right px-4 py-3 font-medium">الفئة</th>
                <th class="text-right px-4 py-3 font-medium">الوصف</th>
                <th class="text-right px-4 py-3 font-medium">الرد</th>
                <th class="text-right px-4 py-3 font-medium">التاريخ</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-50">

            <template x-for="complaint in complaints" :key="complaint.id">

                <tr class="hover:bg-gray-50 transition cursor-pointer" @click="openDetails(complaint)">

                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2.5">

                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-medium flex-shrink-0"
                                :class="complaint.color" x-text="complaint.initials">
                            </div>

                            <span class="font-medium text-gray-700" x-text="complaint.patient.name">
                            </span>

                        </div>
                    </td>

                    <td class="px-4 py-3 text-gray-600 max-w-xs truncate" x-text="complaint.subject">
                    </td>

                    <td class="px-4 py-3 text-gray-500 text-xs" x-text="complaint.target_type">
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs" x-text="complaint.target_type">
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs" x-text="complaint.target_type">
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs" x-text="complaint.target_type">
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs" x-text="complaint.target_type">
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs" x-text="complaint.target_type">
                    </td>
                    <td class="px-4 py-3 text-gray-400 text-xs" x-text="formatDate(complaint.created_at)">
                    </td>

                    <td class="px-4 py-3">

                        <span class="text-xs px-2.5 py-1 rounded-full" :class="status(complaint.status).cls"
                            x-text="status(complaint.status).label">
                        </span>

                    </td>


                    <td class="px-4 py-3">

                        <div class="flex items-center gap-3">

                            <button @click.stop="openDetails(complaint)" class="text-teal-500 hover:text-teal-700">

                                <i class="fa fa-eye text-xs"></i>

                            </button>

                            <button @click.stop="confirmDelete(complaint)" class="text-red-400 hover:text-red-600">

                                <i class="fa fa-trash text-xs"></i>

                            </button>

                        </div>

                    </td>

                </tr>

            </template>

        </tbody>

    </table>

    <div x-show="complaints.length === 0" x-transition
        class="flex flex-col items-center justify-center py-16 text-gray-300">

        <i class="fa fa-flag text-5xl mb-4"></i>

        <p class="text-sm">
            لا توجد شكاوى مطابقة
        </p>

    </div>

</div>
