    {{-- ===================== ADD / EDIT MODAL ===================== --}}
    <div x-show="showVacationModal" x-transition.opacity @click.self="closeModal()" x-cloak
        class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center" dir="rtl">

        <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4 shadow-xl">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-5">

                <h3 class="text-base font-medium text-gray-800"
                    x-text="selectedVacation ? 'تعديل الإجازة' : 'إضافة إجازة'">
                </h3>

                <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 transition">

                    <i class="fa fa-xmark"></i>

                </button>

            </div>

            {{-- Form --}}
            <div class="flex flex-col gap-4">

                {{-- Doctor --}}
                <div>

                    <label class="block text-xs text-gray-500 mb-1.5">
                        الطبيب
                        <span class="text-red-400">*</span>
                    </label>

                    <select x-model="form.doctor_id"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition bg-white">

                        <option value="">
                            اختر الطبيب
                        </option>

                        <template x-for="doctor in doctors" :key="doctor.id">

                            <option :value="doctor.id" x-text="doctor.name">
                            </option>

                        </template>

                    </select>

                </div>

                {{-- Reason --}}
                <div>

                    <label class="block text-xs text-gray-500 mb-1.5">
                        سبب الإجازة
                        <span class="text-red-400">*</span>
                    </label>

                    <input type="text" x-model="form.reason" placeholder="سبب الإجازة"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">

                </div>

                {{-- Dates --}}
                <div class="grid grid-cols-2 gap-3">

                    <div>

                        <label class="block text-xs text-gray-500 mb-1.5">
                            من
                            <span class="text-red-400">*</span>
                        </label>

                        <input type="date" x-model="form.start_date"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">

                    </div>

                    <div>

                        <label class="block text-xs text-gray-500 mb-1.5">
                            إلى
                            <span class="text-red-400">*</span>
                        </label>

                        <input type="date" x-model="form.end_date"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition">

                    </div>

                </div>

                {{-- Duration --}}
                <div x-show="form.start_date && form.end_date"
                    class="bg-teal-50 rounded-lg px-4 py-2.5 text-sm text-teal-700 flex items-center gap-2">

                    <i class="fa fa-calendar-days"></i>

                    <span x-text="'مدة الإجازة: ' + daysBetween(form.start_date, form.end_date) + ' يوم'">
                    </span>

                </div>

                {{-- Notes --}}
                <div>

                    <label class="block text-xs text-gray-500 mb-1.5">
                        ملاحظات
                    </label>

                    <textarea rows="3" x-model="form.notes" placeholder="أي ملاحظات إضافية..."
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-800 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-100 transition resize-none">
                    </textarea>

                </div>

            </div>

            {{-- Footer --}}
            <div class="flex gap-3 mt-5">

                <button @click="closeModal()"
                    class="flex-1 border border-gray-200 text-gray-600 text-sm py-2.5 rounded-lg hover:bg-gray-50 transition">

                    إلغاء

                </button>

                <button @click="saveVacation()"
                    class="flex-1 bg-teal-600 hover:bg-teal-700 text-white text-sm py-2.5 rounded-lg transition">

                    حفظ

                </button>

            </div>

        </div>

    </div>
