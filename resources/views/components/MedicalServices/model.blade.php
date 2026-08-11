<div x-show="showModal" class="fixed inset-0 bg-black/40 flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-5">
        <div class="mb-4">
            <h2 class="text-lg font-bold text-gray-800" x-text="editMode ? 'تعديل سعر الخدمة' : 'إضافة  خدمة جديده'"></h2>
            <p class="text-sm text-gray-500">اختر الخدمة والدكتور وحدد السعر والوصف</p>
        </div>
        <div class="space-y-3">
            <!-- Custom Select: الخدمة -->
            <div class="relative" @click.outside="serviceDropdownOpen = false">
                <button type="button" @click="serviceDropdownOpen = !serviceDropdownOpen"
                    class="w-full flex items-center justify-between bg-gray-50 border border-gray-100 rounded-lg p-2 text-right focus:ring-2 focus:ring-teal-500 outline-none">
                    <span x-text="medicalService.find(s => s.id === form.medicalServiceId)?.name || 'اختر الخدمة'"
                        :class="form.medicalServiceId ? 'text-gray-800' : 'text-gray-400'"></span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform shrink-0"
                        :class="serviceDropdownOpen && 'rotate-180'" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="serviceDropdownOpen" x-transition.opacity x-cloak
                    class="absolute z-50 mt-1 w-full max-h-56 overflow-y-auto bg-white border border-gray-100 rounded-lg shadow-lg">
                    <template x-if="medicalService.length === 0">
                        <div class="px-3 py-2 text-sm text-gray-400">لا توجد خدمات</div>
                    </template>
                    <template x-for="service in medicalService" :key="service.id">
                        <div @click="form.medicalService_id = service.id; serviceDropdownOpen = false"
                            class="px-3 py-2 cursor-pointer text-gray-700 hover:bg-teal-50"
                            :class="form.medicalServiceId === service.id && 'bg-teal-600 text-white hover:bg-teal-600'"
                            x-text="service.name"></div>
                    </template>
                </div>
            </div>
            <!-- Custom Select: الدكتور -->
            <div class="relative" @click.outside="doctorDropdownOpen = false">
                <button type="button" @click="doctorDropdownOpen = !doctorDropdownOpen"
                    class="w-full flex items-center justify-between bg-gray-50 border border-gray-100 rounded-lg p-2 text-right focus:ring-2 focus:ring-teal-500 outline-none">
                    <span x-text="doctors.find(d => d.id === form.doctorId)?.name || 'اختر الدكتور'"
                        :class="form.doctorId ? 'text-gray-800' : 'text-gray-400'"></span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform shrink-0"
                        :class="doctorDropdownOpen && 'rotate-180'" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="doctorDropdownOpen" x-transition.opacity x-cloak
                    class="absolute z-50 mt-1 w-full max-h-56 overflow-y-auto bg-white border border-gray-100 rounded-lg shadow-lg">
                    <template x-if="doctors.length === 0">
                        <div class="px-3 py-2 text-sm text-gray-400">لا يوجد دكاترة</div>
                    </template>
                    <template x-for="doc in doctors" :key="doc.id">
                        <div @click="form.doctorId = doc.id; doctorDropdownOpen = false"
                            class="px-3 py-2 cursor-pointer text-gray-700 hover:bg-teal-50"
                            :class="form.doctorId === doc.id && 'bg-teal-600 text-white hover:bg-teal-600'"
                            x-text="doc.name"></div>
                    </template>
                </div>
            </div>
            <input type="number" x-model="form.price" placeholder="السعر" min="0" step="0.01"
                class="w-full bg-gray-50 border border-gray-100 rounded-lg p-2 focus:ring-2 focus:ring-teal-500 outline-none" />
            <textarea x-model="form.description" placeholder="وصف الخدمة" rows="3"
                class="w-full bg-gray-50 border border-gray-100 rounded-lg p-2 focus:ring-2 focus:ring-teal-500 outline-none resize-none"></textarea>
        </div>
        <div class="flex justify-end gap-2 mt-5">
            <button @click="showModal = false" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                إلغاء
            </button>
            <form method="POST"
                :action="editMode
                    ?
                    `/clinic/services/${form.id}` :
                    '{{ route('clinic.services.store') }}'">
                    @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <input type="hidden" name="id" x-model="form.id">
                <input type="hidden" name="medicalServiceId" x-model="form.medicalServiceId">
                <input type="hidden" name="price" x-model="form.price">
                <input type="hidden" name="description" x-model="form.description">

                <input type="hidden" name="doctorId" x-model="form.doctorId">

                <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition">

                    <span x-text="editMode ? 'تعديل' : 'حفظ'"></span>
                </button>
            </form>
        </div>
    </div>
</div>
