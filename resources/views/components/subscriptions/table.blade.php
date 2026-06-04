   {{-- ===== TABLE ===== --}}
        <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">

                    <thead class="bg-gray-50 text-gray-600 border-b">
                        <tr>
                            <th class="p-4 text-right font-medium">العيادة</th>
                            <th class="p-4 text-right font-medium">الخطة</th>
                            <th class="p-4 text-right font-medium">السعر</th>
                            <th class="p-4 text-right font-medium">البداية</th>
                            <th class="p-4 text-right font-medium">الانتهاء</th>
                            <th class="p-4 text-right font-medium">الحالة</th>
                            <th class="p-4 text-center font-medium">الإجراءات</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        <template x-for="item in filtered" :key="item.id">
                            <tr class="hover:bg-gray-50 transition">

                                <td class="p-4 font-medium" x-text="item.clinic"></td>
                                <td class="p-4 capitalize" x-text="item.plan"></td>
                                <td class="p-4" x-text="item.price + ' EGP'"></td>
                                <td class="p-4" x-text="item.start"></td>
                                <td class="p-4" x-text="item.end"></td>

                                <td class="p-4">
                                    <span class="px-3 py-1 text-xs rounded-full" :class="badgeClass(getStatus(item))"
                                        x-text="statusLabel(getStatus(item))"></span>
                                </td>

                                <td class="p-4 text-center space-x-2 space-x-reverse">
                                    <button @click="openEdit(item)"
                                        class="text-amber-600 hover:text-amber-700 transition font-medium">تعديل</button>
                                    <button @click="renew(item.id)"
                                        class="text-blue-600 hover:text-blue-700 transition font-medium">تجديد</button>
                                    <button @click="deleteItem(item.id)"
                                        class="text-red-600 hover:text-red-700 transition font-medium">حذف</button>
                                </td>

                            </tr>
                        </template>
                    </tbody>

                </table>
            </div>

            {{-- Empty State --}}
            <div x-show="filtered.length === 0" x-cloak class="text-center py-16 text-gray-500">
                <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                <p class="text-sm">لا توجد اشتراكات مطابقة</p>
            </div>

        </div>