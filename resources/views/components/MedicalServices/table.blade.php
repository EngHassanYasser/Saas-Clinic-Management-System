<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500">
            <tr>
                <th class="p-4 text-right font-medium">الخدمة</th>
                <th class="p-4 text-right font-medium">الوصف</th>
                <th class="p-4 text-right font-medium">الدكتور</th>
                <th class="p-4 text-right font-medium">السعر</th>
                <th class="p-4 text-center font-medium">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            <template x-for="item in filteredServices" :key="item.id">
                <tr class="hover:bg-gray-50/60 transition">
                    <td class="p-4 text-gray-800 font-medium" x-text="item.service_name"></td>

                    <td class="p-4 text-gray-500" x-text="item.description"></td>

                    <td class="p-4 text-gray-600" x-text="item.doctor_name"></td>

                    <td class="p-4 text-teal-600 font-semibold" x-text="item.price + ' جنيه'"></td>

                    <td class="p-4">
                        <div class="flex justify-center gap-2">
                            <button @click="openEdit(item)"
                                class="px-3 py-1 rounded-md text-blue-600 hover:bg-blue-50 transition">
                                تعديل
                            </button>
                            <button @click="deleteService(item.id)"
                                class="px-3 py-1 rounded-md text-red-600 hover:bg-red-50 transition">
                                حذف
                            </button>
                        </div>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
</div>
