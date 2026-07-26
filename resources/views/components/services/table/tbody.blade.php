<tbody>
    <template x-for="clinicService in clinicServices" :key="clinicService.id">
        <tr class="hover:bg-gray-50 transition">
            <td class="p-4" x-text="clinicService.service_name ?? ''"></td>
            <td class="p-4 text-gray-600" x-text="clinicService.description"></td>
            <td class="p-4" x-text="clinicService.doctor_name ?? ''"></td>
            <td class="p-4 text-teal-600 font-semibold" x-text="clinicService.price + ' جنيه'"></td>
            <td class="p-4">
                <div class="flex justify-center gap-2">
                    <button @click="openEdit(clinicService)"
                        class="px-3 py-1 text-blue-600 hover:bg-blue-50 rounded-md">
                        تعديل
                    </button>
                    <button @click="deleteService(clinicService.id)"
                        class="px-3 py-1 text-red-600 hover:bg-red-50 rounded-md">
                        حذف
                    </button>
                </div>
            </td>
        </tr>
    </template>
</tbody>
